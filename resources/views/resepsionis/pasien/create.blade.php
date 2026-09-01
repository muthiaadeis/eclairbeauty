@extends('layouts.app')

@section('title', 'Tambah Pasien Baru')

@section('extra_style')
.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.72rem; color: #9CA3AF;
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 6px;
}
.breadcrumb a { color: #9CA3AF; text-decoration: none; }
.breadcrumb a:hover { color: #C17B7B; }
.breadcrumb .bc-active { color: #C17B7B; font-weight: 600; }

.form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    padding: 2rem 2rem 1.75rem;
    margin-top: 1.5rem;
}

/* 2-column grid: section kiri | garis | section kanan */
.form-two-col {
    display: grid;
    grid-template-columns: 1fr 1px 1fr;
    gap: 0 2rem;
    margin-bottom: 1.5rem;
}
.form-col-divider {
    background: #F3F4F6;
    width: 1px;
}

.form-section-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.88rem; font-weight: 600; color: #374151;
    margin-bottom: 1.25rem;
}
.form-section-title svg { width: 16px; height: 16px; color: #C17B7B; flex-shrink: 0; }

.form-group { margin-bottom: 1.1rem; }
.form-label {
    display: block; font-size: 0.82rem;
    font-weight: 500; color: #374151;
    margin-bottom: 0.4rem;
}
.form-label .req { color: #C17B7B; }

.fc {
    width: 100%;
    padding: 0.6rem 0.85rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.875rem; color: #374151;
    background: #fff; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit; box-sizing: border-box;
}
.fc::placeholder { color: #9CA3AF; }
.fc:focus {
    border-color: #C17B7B;
    box-shadow: 0 0 0 3px rgba(193,123,123,0.1);
}
.fc.err { border-color: #EF4444; }

/* Phone +62 prefix */
.phone-wrap { display: flex; }
.phone-prefix {
    padding: 0.6rem 0.75rem;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-right: none;
    border-radius: 10px 0 0 10px;
    font-size: 0.875rem; color: #6B7280;
    white-space: nowrap; align-self: stretch;
    display: flex; align-items: center;
}
.phone-wrap .fc { border-radius: 0 10px 10px 0; }

textarea.fc { resize: vertical; min-height: 82px; }

.field-err { font-size: 0.73rem; color: #EF4444; margin-top: 4px; }

/* Actions */
.form-actions {
    display: flex; align-items: center;
    justify-content: flex-end; gap: 10px;
    padding-top: 1.25rem;
    border-top: 1px solid #F3F4F6;
}
.btn-cancel {
    padding: 0.6rem 1.4rem;
    background: none; border: none;
    font-size: 0.875rem; color: #6B7280;
    cursor: pointer; text-decoration: none;
    border-radius: 10px; transition: background 0.15s;
}
.btn-cancel:hover { background: #F3F4F6; }
.btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.6rem 1.4rem;
    background: #C17B7B; color: #fff;
    border: none; border-radius: 10px;
    font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: background 0.2s;
}
.btn-save:hover { background: #B06B6B; }
.btn-save svg { width: 15px; height: 15px; }
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('resepsionis.dashboard') }}">Manajemen</a>
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <a href="{{ route('pasien.index') }}">Data Pasien</a>
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <span class="bc-active">Tambah Pasien</span>
</div>

<h1 class="page-title">Tambah Pasien Baru</h1>
<p class="page-subtitle">Lengkapi formulir di bawah ini untuk mendaftarkan pasien baru ke dalam sistem.</p>

<form method="POST" action="{{ route('pasien.store') }}">
@csrf
<div class="form-card">

    <div class="form-two-col">

        {{-- ── Kolom Kiri: Informasi Personal ── --}}
        <div>
            <div class="form-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Personal
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="nama_pasien"
                       class="fc {{ $errors->has('nama_pasien') ? 'err' : '' }}"
                       placeholder="Contoh: Siti Aminah"
                       value="{{ old('nama_pasien') }}">
                @error('nama_pasien')<div class="field-err">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon <span class="req">*</span></label>
                <div class="phone-wrap">
                    <span class="phone-prefix">+62</span>
                    <input type="text" name="no_hp"
                           class="fc {{ $errors->has('no_hp') ? 'err' : '' }}"
                           placeholder="812-XXXX-XXXX"
                           value="{{ old('no_hp') }}">
                </div>
                @error('no_hp')<div class="field-err">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email_google" class="fc"
                       placeholder="nama@email.com"
                       value="{{ old('email_google') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="fc">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                </select>
            </div>
        </div>

        {{-- Garis pemisah vertikal --}}
        <div class="form-col-divider"></div>

        {{-- ── Kolom Kanan: Data Tambahan ── --}}
        <div>
            <div class="form-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Data Tambahan
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="fc"
                       value="{{ old('tanggal_lahir') }}">
                @error('tanggal_lahir')<div class="field-err">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="fc"
                          placeholder="Nama jalan, nomor rumah, kecamatan, kota...">{{ old('alamat') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Awal</label>
                <textarea name="catatan_awal" class="fc"
                          placeholder="Keluhan utama atau alergi obat...">{{ old('catatan_awal') }}</textarea>
            </div>
        </div>

    </div>{{-- /form-two-col --}}

    <div class="form-actions">
        <a href="{{ route('pasien.index') }}" class="btn-cancel">Batal</a>
        <button type="submit" class="btn-save">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            Simpan Data Pasien
        </button>
    </div>

</div>
</form>

@endsection
