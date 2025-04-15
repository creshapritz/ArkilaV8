@extends('layouts.admin-settings')

@section('content')
    <style>
        .form {
            background-color: #FFA500;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            margin-top: 1.5rem;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
            display: block;
        }


        .logo-preview {
            height: 100px;
            /* smaller size */
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .try{
            padding: 20px;
        }
        .btn-submit {
            background-color: #FFA500;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
    </style>

    <div class="try">
        <h2 class="text-2xl font-bold mb-4">Company Management</h2>

        {{-- Current Logo --}}
        @if ($currentLogo)
            <div class="mb-4">
                <label class="block font-semibold mb-2">Current Logo:</label>
                <img src="{{ asset('storage/' . $currentLogo) }}" alt="Current Logo" class="logo-preview">
            </div>
        @endif

        {{-- Upload New Logo --}}
        <form class="form1" action="{{ route('admin.updateLogo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-5">
                <label for="site_logo" class="block font-semibold mb-2">Upload New Logo:</label>
                <input type="file" name="site_logo" id="site_logo" accept="image/*" onchange="previewLogo()" required>
            </div>

            {{-- Logo Preview --}}
            <div id="logoPreview" class="mb-4" style="display: none;">
                <label class="block font-semibold mb-2">New Logo Preview:</label>
                <img id="previewImage" src="#" alt="Logo Preview" class="logo-preview">
            </div>

            <button type="submit" class="btn-submit">
                Update Logo
            </button>
        </form>
    </div>
    <script>
        function previewLogo() {
            const input = document.getElementById('site_logo');
            const previewContainer = document.getElementById('logoPreview');
            const previewImage = document.getElementById('previewImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection