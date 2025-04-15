<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{


    
    public function index()
    {
        $drivers = Driver::all(); 
        return view('admin.drivers.index', compact('drivers'));
    }

    
    public function create()
    {
        $partners = \App\Models\Partner::all();
        $driverAdmins = \App\Models\DriverAdmin::all();

        return view('admin.drivers.add-driver', compact('partners', 'driverAdmins'));
    }



   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers',
            'contact_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'second_id_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'second_id_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

       
        if ($request->hasFile('profile_picture')) {
            $validatedData['profile_picture'] = $request->file('profile_picture')->store('drivers', 'public');
        }
        if ($request->hasFile('license_front')) {
            $validatedData['license_front'] = $request->file('license_front')->store('licenses', 'public');
        }
        if ($request->hasFile('license_back')) {
            $validatedData['license_back'] = $request->file('license_back')->store('licenses', 'public');
        }
        if ($request->hasFile('second_id_front')) {
            $validatedData['second_id_front'] = $request->file('second_id_front')->store('second_ids', 'public');
        }
        if ($request->hasFile('second_id_back')) {
            $validatedData['second_id_back'] = $request->file('second_id_back')->store('second_ids', 'public');
        }


        $partner = \App\Models\Partner::find($request->partner_id);
        if ($partner) {
            $validatedData['company_name'] = $partner->company_name;
        }

        // Create the driver
        Driver::create($validatedData);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver added successfully!');
    }



    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.show', compact('driver'));
    }



    public function archive($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 'Archived';
        $driver->save();

        return redirect()->route('admin.drivers.index')->with('success', 'Driver archived successfully!');
    }
    public function toggleStatus($id)
{
    $driver = Driver::findOrFail($id);
    $driver->status = $driver->status === 'Active' ? 'Inactive' : 'Active';
    $driver->save();

    return response()->json(['status' => $driver->status]);
}

}
