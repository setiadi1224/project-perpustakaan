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
}
</style>

<div class="main-content">

    @if(session('success'))
        <div style="background:#10b981; color:white; padding:10px; border-radius:8px; margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-card">

        <!-- FOTO -->
        <div class="profile-left">

            @if($user->foto)
                <img src="{{ asset('storage/foto/' . $user->foto) }}" class="profile-img">
            @else
                <div class="profile-img"></div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="file" name="foto">

        </div>

        <!-- FORM -->
        <div class="profile-right">
            <h3>Edit Profile</h3>

                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}">
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}">
                </div>

                <div class="input-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" value="{{ $user->no_telepon }}">
                </div>

                <div class="input-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="{{ $user->alamat }}">
                </div>

                <button class="btn-simpan">Simpan Perubahan</button>
            </form>

        </div>

    </div>

</div>

@endsection