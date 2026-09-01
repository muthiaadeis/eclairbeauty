@extends('layouts.app')

@section('title', 'Profil - Eclair Beauty Clinic')

@section('extra_style')
.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}
.profile-col-title {
    font-size: 14px;
    font-weight: 700;
    color: #3A3A3A;
    margin-bottom: 18px;
}
.form-group { margin-bottom: 16px; }
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #6B6B6B;
    display: block;
    margin-bottom: 6px;
}
.form-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    font-size: 14px;
    color: #3A3A3A;
}
.form-input:focus { outline: none; border-color: #C17B7B; }
.form-input:disabled { background: #FAF6F2; color: #9B9B9B; }
.form-error { color: #C0392B; font-size: 12px; margin-top: 4px; }

@media (max-width: 768px) {
    .profile-grid { grid-template-columns: 1fr; }
}
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Kelola data akun dan password kamu</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <form method="POST" action="{{ route('profil.update') }}">
        @csrf
        @method('PUT')

        <div class="profile-grid">
            {{-- KOLOM KIRI: INFORMASI AKUN --}}
            <div>
                <div class="profile-col-title">Informasi Akun</div>

                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-input" value="{{ old('nama', $user->nama) }}">
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" value="{{ $user->username }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" class="form-input" value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                    @error('nomor_telepon') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- KOLOM KANAN: GANTI PASSWORD --}}
            <div>
                <div class="profile-col-title">Ganti Password (opsional)</div>

                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="password_lama" class="form-input">
                    @error('password_lama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password_baru" class="form-input">
                    @error('password_baru') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" class="form-input">
                </div>
            </div>
        </div>

        <div style="border-top:1px solid #F0E8E2; margin-top:8px; padding-top:20px;">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
