<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/assets/img/favicon-96x96.png" sizes="96x96" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="{{ asset('assets/js/landpage.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Rate us</title>

    <style>
        .review-form-container {
            max-width: 600px;
            margin: 150px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .review-form-container h2 {
            margin-bottom: 15px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            height: 45px;
        }

        .form-group textarea {
            resize: none;
            width: 100%;
            height: 45px;
            overflow: hidden;
        }

        .btn-confirm {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: #F07324;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            background: #d95c1e;
        }
    </style>

</head>

<body>

    <section>
        <!-------------------------------------------------------- NAVBAR------------------------------------------------------------->
        <nav class="navbar">
            <div class="navbar-left">
                <img src="{{ asset('assets/img/whitelogoarkila.png') }}" alt="Logo" class="navbar-logo">
            </div>
            <div class="navbar-right">
                <button class="btn-bookings" onclick="window.location.href='{{ route('my-bookings') }}'">My Bookings</button>
                <button class="btn-partner" onclick="window.location.href='{{ route('welcome_partner') }}'">Become a partner</button>
                @if(Auth::check())
                    <button class="btn-client">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ Auth::user()->profile_picture }}" alt="Profile Picture" class="navbar-profile-pic">
                        @else
                            <i class='bx bxs-user-circle profile-icon'></i> 
                        @endif
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-client-login">Login</a>
                @endif
            </div>
        </nav>

        <!---------------------------------------------------- SIDEBAR------------------------------------------------------>
        <div class="sidebar">
            <a href="#menu" class="menu-link" id="menu-button">
                <i class='bx bx-menu' id="menu-icon"></i>
                <span class="menu-text">ARKILA</span>
            </a>
            <ul>
                <li><a href="{{ route('welcome') }}"><i class='bx bx-home'></i> <span>Home</span></a></li>
                <li><a href="{{ route('welcome_about') }}"><i class='bx bx-info-circle'></i> <span>About Us</span></a></li>
                <li><a href="{{ route('welcome_vehicles') }}"><i class='bx bx-car'></i> <span>Vehicles</span></a></li>
                <li><a href="{{ route('welcome_services') }}"><i class='bx bx-wrench'></i> <span>Services</span></a></li>
                <li><a href="{{ route('welcome_rent') }}"><i class='bx bx-key'></i> <span>Rent</span></a></li>
                <li><a href="{{ route('welcome_contact') }}"><i class='bx bx-envelope'></i> <span>Contact Us</span></a></li>
                <li><a href="{{ route('welcome_partner') }}"><i class='bx bx-user-plus'></i><span>Partnership</span></a></li>
                <li><a href="{{ route('welcome_settings') }}"><i class='bx bx-cog'></i> <span>Settings</span></a></li>
                <li><a href="javascript:void(0);" id="logout-link"><i class='bx bx-log-out'></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- ---------------------------------------------------- MAIN ------------------------------------------------------>
        <div class="review-form-container">
    <h2>Leave a Review</h2>
    
    <form action="{{ route('reviews.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="car_id">Select Car:</label>
         
            <select name="car_id" id="car_id" required>
                @foreach ($cars as $car)
                    <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->type }} </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1-5):</label>
            <select name="rating" id="rating" required>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>
        </div>

        <div class="form-group">
            <label for="review">Your Review:</label>
            <textarea name="review" id="review" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn-confirm">Submit Review</button>
    </form>
</div>

        <!----------------------------------------------------- VIDEO 2 -------------------------------------------------------->
        <section class="video2">
            <div class="video2">
                <video src="{{ asset('assets/img/output.mp4') }}" autoplay loop muted></video>
            </div>
        </section>

        <!----------------------------------------------------- FOOTER -------------------------------------------------------->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('assets/img/whitelogoarkila.png') }}" alt="Arkila Logo" class="footer-logo">
                    <div class="footer-links">
                        <a href="#" class="footer-link">Home</a>
                        <span class="footer-link">About Us</span>
                        <span class="footer-link">Vehicles</span>
                        <span class="footer-link">Services</span>
                        <span class="footer-link">Rent</span>
                        <span class="footer-link">Contact Us</span>
                        <span class="footer-link">Partnership</span>
                    </div>
                </div>
                <div class="footer-section center-section">
                    <h3 class="footer-title">Payment Method</h3>
                    <div class="footer-links">
                        <span class="payment-method-link">Cash</span>
                        <span class="payment-method-link">E-Wallet</span>
                        <span class="payment-method-link">Card</span>
                        <span class="payment-method-link">Cheque</span>
                    </div>
                </div>
                <div class="footer-section right-section">
                    <h3 class="footer-title">Permits</h3>
                    <div class="footer-links">
                        <span class="payment-method-link">Business Permit</span>
                        <span class="payment-method-link">DTI Permit</span>
                        <span class="payment-method-link">Barangay Permit</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom-links">
                <a href="#" class="footer-bottom-link">Terms and Condition</a>
                <a href="#" class="footer-bottom-link">Privacy Policy</a>
                <a href="#" class="footer-bottom-link">FAQ's</a>
            </div>
            <hr class="footer-line">
            <div class="footer-social-media">
                <p>&copy; 2024-2025 ARKILA. All Rights Reserved.</p>
                <a href="#" class="social-media-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-media-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-media-link"><i class="fab fa-google"></i></a>
                <a href="#" class="social-media-link"><i class="fab fa-instagram"></i></a>
            </div>
        </footer>
    </section>

    <script>
        document.getElementById('logout-link').addEventListener('click', function (e) {
            e.preventDefault(); 
            Swal.fire({
                title: 'Are you sure you want to logout?',
                text: "You will need to log in again to continue.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F07324',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                  
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('logout') }}';

                    var csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
 </script>

</body>

</html>