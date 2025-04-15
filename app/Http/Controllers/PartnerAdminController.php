<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\Booking;
use App\Models\Client;
use FPDF;
use App\Models\Driver;
use App\Models\DriverAdmin;
use App\Models\Partner;
use App\Models\Admin;
use App\Notifications\NewDriverCreated;
date_default_timezone_set('Asia/Manila');

class PartnerAdminController extends Controller
{



    public function showLoginForm()
    {
        return view('partners_admin.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::guard('partner_admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('partners_admin.dashboard');
        }

        return back()->withErrors(['error' => 'Invalid login credentials.']);
    }


    public function dashboard(Request $request)
    {
        $partner = Auth::guard('partner_admin')->user()->partner;

        if (!$partner) {
            return back()->with('error', 'You are not associated with any partner.');
        }

        // Filter cars using query with optional conditions
        $cars = Car::where('partner_id', $partner->id)
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('brand'), fn($q) => $q->where('brand', $request->brand))
            ->when($request->filled('color'), fn($q) => $q->where('color', $request->color))
            ->get();

        // Get total unique clients for this partner using simpler query
        $totalClients = Booking::whereHas('car', function ($q) use ($partner) {
            $q->where('partner_id', $partner->id);
        })->distinct('client_id')->count('client_id');

        return view('partners_admin.dashboard', compact('cars', 'totalClients'));
    }


    public function showVehicle($id)
    {
        $partner = Auth::guard('partner_admin')->user()->partner;

        // Ensure car exists and belongs to the authenticated partner
        $car = Car::where('id', $id)->where('partner_id', $partner->id)->first();

        if (!$car) {
            return back()->with('error', 'Car not found or you are not authorized to view this car.');
        }

        return view('partners_admin.cars.show', compact('car'));
    }

    public function showCar($id)
    {
        $partner = Auth::guard('partner_admin')->user()->partner;

        if (!$partner) {
            return back()->with('error', 'You are not associated with any partner.');
        }

        $car = Car::where('id', $id)->where('partner_id', $partner->id)->first();

        if (!$car) {
            return back()->with('error', 'Car not found or does not belong to your company.');
        }

        return view('partners_admin.cars.show', compact('car'));
    }
    public function showChecklist($id)
    {
        $booking = Booking::with(['client', 'car'])->findOrFail($id);
        return view('partners_admin.checklist', compact('booking'));
    }
    public function submitChecklist(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $checklist = $request->input('items', []);
        $booking->update([
            'checklist' => json_encode($checklist),
        ]);

        return redirect()->route('partners_admin.bookings')->with('success', 'Checklist submitted successfully!');
    }





    public function generateChecklistPDF($id)
    {
        $booking = Booking::with('client', 'car')->findOrFail($id);

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();


        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 15, ($booking->car->company_name ?? 'Partner') . ' Vehicle Return Checklist', 0, 1, 'C');
        $pdf->Ln(5);




        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Ln(10);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Customer Information', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 8, 'Name: ' . ($booking->client->first_name ?? 'N/A') . ' ' .
            ($booking->client->middle_name ?? '') . ' ' .
            ($booking->client->last_name ?? 'N/A'), 0, 1, 'L');

        $pdf->Cell(0, 8, 'Reservation Number: ' . $booking->id, 0, 1, 'L');
        $pdf->Cell(0, 8, 'Vehicle Type: ' . ($booking->car->brand ?? 'N/A'), 0, 1, 'L');
        $pdf->Cell(0, 8, 'Plate Number: ' . ($booking->car->platenum ?? 'N/A'), 0, 1, 'L');
        $pdf->Cell(0, 8, 'Return Date: ' . now()->format('Y-m-d'), 0, 1, 'L');
        $pdf->Cell(0, 8, 'Return Time: ' . now()->format('h:i A'), 0, 1, 'L');



        $sections = [
            'Vehicle Exterior Condition' => [
                'No visible dents, scratches, or damages',
                'Clean exterior',
                'All side mirrors and lights intact',
                'Windshield and windows in good condition',
            ],
            'Vehicle Interior Condition' => [
                'Clean seats, carpets, and dashboard',
                'No stains, tears, or damages',
                'All controls and electronics are functioning',
                'No personal belongings left behind',
            ],
            'Fuel and Mileage' => [
                'Fuel level returned as per policy (Full/Half/Empty)',
                'Mileage recorded: ___________________',
                'No unusual mileage reported',
            ],
            'Tires and Spare' => [
                'Tires in good condition (No punctures or wear)',
                'Spare tire available and in good condition',
                'Tools (Jack, wrench) provided and intact',
            ],
            'Accessories and Equipment' => [
                'Car manual and registration papers present',
                'Spare key returned',
                'Dashboard items (e.g., GPS, infotainment) working',
                'First aid kit and emergency tools available',
            ],
            'Additional Checks' => [
                'No warning lights on dashboard',
                'No unusual engine noises',
                'Air conditioning and heating systems working',
                'Car returned on time',
            ],
        ];



        foreach ($sections as $section => $items) {
            $pdf->Ln(10);
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->Cell(0, 10, $section, 0, 1, 'L');
            $pdf->Ln(5);

            $pdf->SetFont('Helvetica', '', 12);
            foreach ($items as $item) {
                $pdf->Cell(0, 8, '[ ] ' . $item, 0, 1, 'L');
            }
        }


        $pdf->Ln(15);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Customer Acknowledgement', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 8, 'I confirm the vehicle was returned in the stated condition.', 0, 1, 'L');
        $pdf->Cell(0, 8, 'Signature: _____________________________', 0, 1, 'L');
        $pdf->Cell(0, 8, 'Date: ____________________________________', 0, 1, 'L');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Staff Verification', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 8, 'Signature: _____________________________', 0, 1, 'L');
        $pdf->Cell(0, 8, 'Name: ____________________________________', 0, 1, 'L');
        $pdf->Cell(0, 8, 'Date: ____________________________________', 0, 1, 'L');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="vehicle_return_checklist.pdf"');
        $pdf->Output();
        exit;
    }


    public function logout()
    {
        Auth::guard('partner_admin')->logout();
        session()->flush(); // Clear all session data
        return redirect()->route('partners_admin.login');
    }


    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $request->input('status');
        $booking->save();

        return back()->with('success', 'Booking status updated successfully!');
    }

    public function transactionHistory()
    {
        $partnerCompanyName = Auth::guard('partner_admin')->user()->firstname ?? 'N/A';

        $bookings = Booking::with('client', 'car')
            ->whereIn('status', ['Partially Paid', 'Paid', 'Returned'])
            ->whereHas('car', function ($query) use ($partnerCompanyName) {
                $query->where('company_name', $partnerCompanyName);
            })
            ->get();

        return view('partners_admin.transaction_history', compact('bookings'));
    }



    public function partnerBookings(Request $request)
    {
        $partner = Auth::guard('partner_admin')->user()->partner;

        if (!$partner) {
            return back()->with('error', 'You are not associated with any partner. Please contact support.');
        }


        $query = Booking::whereHas('car', function ($q) use ($partner) {
            $q->where('partner_id', $partner->id);
        });


        if ($request->filled('company_name')) {
            $query->whereHas('car', function ($q) use ($request) {
                $q->where('company_name', $request->company_name);
            });
        }

        $bookings = $query->get();


        $companyNames = Car::where('partner_id', $partner->id)
            ->distinct()
            ->pluck('company_name');

        return view('partners_admin.bookings.index', compact('bookings', 'companyNames'));
    }

    public function cars()
    {
        $partnerAdmin = Auth::guard('partner_admin')->user();


        $cars = Car::where('partner_id', $partnerAdmin->partner_id)->get();

        return view('partners_admin.cars.index', compact('cars'));
    }

    public function show($id)
    {

        $partnerAdmin = Auth::guard('partner_admin')->user();

        // Fetch the client associated with the partner's partner_id
        $client = Client::where('id', $id)
            ->whereIn('id', function ($query) use ($partnerAdmin) {
                $query->select('client_id')
                    ->from('bookings')
                    ->join('cars', 'bookings.car_id', '=', 'cars.id')
                    ->where('cars.partner_id', $partnerAdmin->partner_id);
            })
            ->firstOrFail();


        return view('partners_admin.clients.show', compact('client'));
    }


    public function clients()
    {
        $partner = auth('partner_admin')->user();

        $clients = Client::whereIn('id', function ($query) use ($partner) {
            $query->select('client_id')
                ->from('bookings')
                ->join('cars', 'bookings.car_id', '=', 'cars.id')
                ->where('cars.partner_id', $partner->partner_id);
        })->get();

        return view('partners_admin.clients.index', compact('clients'));
    }
    public function drivers()
    {
        $partnerAdmin = Auth::guard('partner_admin')->user();

        $driverAdmins = DriverAdmin::where('partner_id', $partnerAdmin->partner_id)->get();


        $drivers = Driver::whereIn('id', $driverAdmins->pluck('id'))->get();

        return view('partners_admin.drivers.index', compact('drivers'));
    }


    public function showDriver($id)
    {
        $partnerAdmin = Auth::guard('partner_admin')->user();

     
        $driver = Driver::where('drivers.id', $id)
            ->whereIn('drivers.id', function ($query) use ($partnerAdmin) {
                $query->select('bookings.driver_id')  
                    ->from('bookings')
                    ->join('cars', 'bookings.car_id', '=', 'cars.id')
                    ->where('cars.partner_id', $partnerAdmin->partner_id);
            })
            ->firstOrFail();

        return view('partners_admin.drivers.show', compact('driver'));
    }

    public function archiveDriver($id)
    {
        $partnerAdmin = Auth::guard('partner_admin')->user();

      
        $driver = Driver::where('partner_id', $partnerAdmin->partner_id)->where('id', $id)->firstOrFail();

        $driver->status = 'Archived';  
        $driver->save();
        return redirect()->route('partners_admin.drivers.index')->with('success', 'Driver has been archived.');
    }


    public function createDriver()
    {
        $driverAdmins = DriverAdmin::all();
        $partners = Partner::all();
        return view('partners_admin.drivers.create', compact('driverAdmins', 'partners'));
    }



    public function storeDriver(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:drivers,email',
            'contact_number' => 'required',
            'partner_id' => 'required|exists:partners,id',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'license_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'license_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'second_id_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'second_id_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Check if the driver with the same email already exists
        $existingDriver = Driver::where('email', $request->email)->first();

        if ($existingDriver) {
            return redirect()->back()->with('error', 'Driver with this email already exists.');
        }

        $driver = new Driver();
        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->contact_number = $request->contact_number;
        $driver->partner_id = $request->partner_id;

        $partner = Partner::find($request->partner_id);
        $driver->company_name = $partner ? $partner->company_name : null;

        $driver->profile_picture = $request->file('profile_picture')->move(public_path('uploads/drivers'), $request->file('profile_picture')->getClientOriginalName());
        $driver->license_front = $request->file('license_front')->move(public_path('uploads/drivers'), $request->file('license_front')->getClientOriginalName());
        $driver->license_back = $request->file('license_back')->move(public_path('uploads/drivers'), $request->file('license_back')->getClientOriginalName());
        $driver->second_id_front = $request->file('second_id_front')->move(public_path('uploads/drivers'), $request->file('second_id_front')->getClientOriginalName());
        $driver->second_id_back = $request->file('second_id_back')->move(public_path('uploads/drivers'), $request->file('second_id_back')->getClientOriginalName());
        

       
        $driver->status = 'pending';

        $driver->save();

       
        $superAdmins = Admin::where('role', 'admin')->get();
        foreach ($superAdmins as $admin) {
            $admin->notify(new NewDriverCreated($driver));
        }

        return redirect()->route('partners_admin.drivers.create')->with('success', 'Driver successfully added!');
    }

    









}
