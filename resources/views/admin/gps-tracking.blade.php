@extends('layouts.admin')

@section('content')
    <style>
        body {
            font-family: 'Sf Pro Display', sans-serif;
            background-color: #f4f4f4;
        }

        .gps-container {
            max-width: 1000px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .gps-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .gps-header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        .client-card.active {
            background-color: #d6f5d6;
            border: 1px solid #F07324;
        }
    </style>
    <div class="gps-container" style="display: flex; gap: 20px;">

        <div style="width: 35%; max-height: 450px; overflow-y: auto; border-right: 1px solid #ddd; padding-right: 10px;">
            <h2 style="font-size: 18px; margin-bottom: 10px;">Clients</h2>
            @foreach(\App\Models\Booking::with('car', 'client')->latest()->get() as $b)
                <div class="client-card" data-car-id="{{ $b->car->id }}" data-client-id="{{ $b->client->id }}"
                    style="cursor: pointer; padding: 10px; margin-bottom: 10px; background: #f8f8f8; border-radius: 10px;">
                    <strong>{{ $b->client->first_name }} {{ $b->client->last_name }}</strong><br>
                    <small>Car: {{ $b->car->brand  }} {{ $b->car->platenum  }}</small>
                </div>

            @endforeach
        </div>


        <div style="width: 65%;">
            <div class="gps-header">
                <h1>GPS Tracking</h1>
            </div>
            <div id="map"></div>
            <div id="last-updated" style="margin-top: 10px; font-size: 14px; color: #555;">
                Last updated: <span id="updated-time">Not yet updated</span>
                <span id="gps-warning" style="color: red; font-weight: bold; margin-left: 10px;"></span>
            </div>


        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let map, marker;
            let intervalId = null;

            function initMap(lat, lng) {
                if (!map) {
                    map = L.map('map').setView([lat, lng], 17);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup("<b>Client's Location</b>").openPopup();
                } else {
                    map.setView([lat, lng], 17);
                    marker.setLatLng([lat, lng]);
                }
            }

            function fetchAndUpdateMap(carId, clientId) {
                fetch(`/admin/gps/${carId}/${clientId}`)
                    .then(res => res.json())
                    .then(data => {

                        if (data.latitude && data.longitude) {
                            initMap(data.latitude, data.longitude);

                            const now = new Date();
                            lastUpdateTime = now;

                            const timeString = now.toLocaleTimeString();
                            document.getElementById('updated-time').innerText = timeString;
                        }
                        else {
                            alert("No GPS data available for this client.");
                        }
                    })
                    .catch(err => console.error('Error fetching GPS data:', err));
            }

            function startLiveUpdate(carId, clientId) {

                if (intervalId) {
                    clearInterval(intervalId);
                }

                fetchAndUpdateMap(carId, clientId);
                intervalId = setInterval(() => {
                    fetchAndUpdateMap(carId, clientId);
                }, 10000);
            }
         

            let lastUpdateTime = null;

            function checkIfOutdated() {
                const now = new Date();
                if (lastUpdateTime) {
                    const diff = Math.floor((now - lastUpdateTime) / 1000);
                    const warningEl = document.getElementById('gps-warning');

                    if (diff > 30) {
                        warningEl.innerText = "⚠️ No update received in the last 30 seconds.";
                    } else {
                        warningEl.innerText = "";
                    }
                }
            }


            const defaultCard = document.querySelector('.client-card');
            if (defaultCard) {
                const defaultCarId = defaultCard.getAttribute('data-car-id');
                const defaultClientId = defaultCard.getAttribute('data-client-id');
                defaultCard.classList.add('active');
                startLiveUpdate(defaultCarId, defaultClientId);
            }

            document.querySelectorAll('.client-card').forEach(card => {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.client-card').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    const carId = this.getAttribute('data-car-id');
                    const clientId = this.getAttribute('data-client-id');



                    startLiveUpdate(carId, clientId);
                });
            });
        });
        setInterval(checkIfOutdated, 5000); // check every 5 seconds

    </script>

@endsection