@extends('user.layouts.app')

@section('content')
<style>
    .main-content {
        padding: 20px;
    }

    .profile-card {
        background: #f9fafb;
        padding: 30px;
        border-radius: 16px;
        display: flex;
        gap: 30px;
        align-items: center;
        flex-wrap: wrap;
    }

    .profile-left {
        text-align: center;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        background: #d1d5db;
        margin-bottom: 10px;
    }

    .profile-right {
        flex: 1;
        min-width: 250px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group label {
        display: block;
        margin-bottom: 5px;
    }

    .input-group input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .btn-simpan {
        background: #10b981;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .alert-success {
        background: #10b981;
        color: white;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .alert-error {
        background: #ef4444;
        color: white;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
</style>

<div class="main-content">

    <!-- Success message -->
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error message -->
    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin:0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="profile-card">

            <div class="profile-left">
                @if ($user->foto)
                    <img id="preview-foto" src="{{ asset('storage/foto/' . $user->foto) }}" class="profile-img">
                @else
                    <img id="preview-foto" src="https://via.placeholder.com/120" class="profile-img">
                @endif
                <input type="file" name="foto" id="foto-input" accept="image/*">
            </div>

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

                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>

        </div>
    </form>
</div>

<script>
    // Preview foto sebelum upload
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