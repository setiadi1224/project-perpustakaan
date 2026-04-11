@extends('user.layouts.app')

@section('content')

    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
        }

        .main-content {
            padding: 20px;
        }

        /* ALERT */
        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        /* CARD */
        .profile-card {
            background: #1e293b;
            padding: 30px;
            border-radius: 18px;
            display: flex;
            gap: 30px;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            border: 1px solid #334155;
        }

        /* LEFT */
        .profile-left {
            text-align: center;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            background: #334155;
            margin-bottom: 10px;
            border: 2px solid #3b82f6;
        }

        /* RIGHT */
        .profile-right {
            flex: 1;
            min-width: 250px;
        }

        .profile-right h3 {
            margin-bottom: 15px;
            color: #f1f5f9;
        }

        /* INPUT */
        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: #94a3b8;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #0f172a;
            color: #e5e7eb;
            outline: none;
            transition: 0.2s;
        }

        .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* BUTTON */
        .btn-simpan {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.25);
        }

        .btn-simpan:hover {
            transform: translateY(-2px);
        }
    </style>

    <div class="main-content">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="profile-card">

                {{-- LEFT --}}
                <div class="profile-left">
                    @if ($user->foto)
                        <img id="preview-foto" src="{{ asset('storage/foto/' . $user->foto) }}" class="profile-img">
                    @else
                        <img id="preview-foto" src="https://via.placeholder.com/120" class="profile-img">
                    @endif

                    <input type="file" name="foto" id="foto-input" accept="image/*"
                        style="margin-top:10px; color:#94a3b8;">
                </div>

                {{-- RIGHT --}}
                <div class="profile-right">
                    <h3>Edit Profile</h3>

                    <div class="input-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="input-group">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                    </div>

                    <div class="input-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}">
                    </div>

                    <button type="submit" class="btn-simpan">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('foto-input');
        const previewFoto = document.getElementById('preview-foto');

        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFoto.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection
