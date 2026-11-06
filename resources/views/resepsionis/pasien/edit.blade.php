@extends('layouts.app')

@section('title', 'Edit Pasien - Eclair Beauty Clinic')

@section('extra_style')
.form-group { margin-bottom: 20px; }
.form-label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    color: #6B6B6B;
    font-weight: 600;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    font-size: 14px;
    color: #3A3A3A;
    outline: none;
    background: #fff;
}
.form-control:focus { border-color: #C17B7B; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-error { color: #C0392B; font-size: 12px; margin-top: 4px; }
.form-actions { margin-top: 8px; display: flex; gap: 12px; }

/* ── Custom Dropdown Jenis Kelamin ── */
.custom-dropdown-wrap {
    position: relative;
    width: 100%;
}
.custom-dropdown-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    font-size: 14px;
    color: #3A3A3A;
    background: #fff;
    cursor: pointer;
    outline: none;
    transition: all 0.2s;
    font-family: inherit;
}
.custom-dropdown-btn:hover { border-color: #C17B7B; }
.custom-dropdown-btn.open {
    border-color: #C17B7B;
    box-shadow: 0 0 0 3px rgba(193,123,123,0.1);
}
.custom-dropdown-btn svg {
    width: 14px;
    height: 14px;
    color: #9CA3AF;
    transition: transform 0.2s;
}
.custom-dropdown-btn.open svg {
    transform: rotate(180deg);
    color: #C17B7B;
}
.custom-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #F0E8E2;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    z-index: 60;
    overflow: hidden;
}
.custom-dropdown-menu.show { display: block; }
.cd-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 10px 14px;
    border: none;
    background: none;
    font-size: 13.5px;
    color: #3A3A3A;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    text-align: left;
}
.cd-item:hover {
    background: #FAF6F2;
    color: #C17B7B;
}
.cd-item.selected {
    background: #FAF6F2;
    color: #C17B7B;
    font-weight: 600;
}
.cd-item .cd-check {
    display: none;
    color: #C17B7B;
}
.cd-item.selected .cd-check {
    display: block;
}

/* Hilangkan panah spin atas-bawah di input date bawaan browser */
input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    display: none;
}
input[type="date"] {
    -moz-appearance: textfield;
}
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s;
}
input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Edit Data Pasien</h1>
        <p class="page-subtitle">Perbarui informasi {{ $pasien->nama_pasien }}</p>
    </div>
    <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Kembali</a>
</div>

<div class="card" style="max-width:720px;">
    <form method="POST" action="{{ route('pasien.update', $pasien->id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama_pasien" class="form-control" value="{{ old('nama_pasien', $pasien->nama_pasien) }}">
                @error('nama_pasien') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nomor HP *</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pasien->no_hp) }}">
                @error('no_hp') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control {{ $errors->has('tanggal_lahir') ? 'err' : '' }}"
                       value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}">
                @error('tanggal_lahir') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelamin</label>
                @php
                    $genderVal = old('jenis_kelamin', $pasien->jenis_kelamin);
                @endphp
                <input type="hidden" name="jenis_kelamin" id="inputJenisKelamin" value="{{ $genderVal }}">
                <div class="custom-dropdown-wrap">
                    <button type="button" class="custom-dropdown-btn" id="btnDropdownGender" onclick="toggleDropdownGender()">
                        <span id="labelGender">{{ $genderVal == 'P' ? 'Perempuan' : ($genderVal == 'L' ? 'Laki-laki' : 'Pilih jenis kelamin') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="custom-dropdown-menu" id="menuDropdownGender">
                        <button type="button" class="cd-item {{ $genderVal == 'P' ? 'selected' : '' }}" data-val="P" onclick="selectGender('P', 'Perempuan')">
                            <span>Perempuan</span>
                            <svg class="cd-check" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </button>
                        <button type="button" class="cd-item {{ $genderVal == 'L' ? 'selected' : '' }}" data-val="L" onclick="selectGender('L', 'Laki-laki')">
                            <span>Laki-laki</span>
                            <svg class="cd-check" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $pasien->alamat) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Data</button>
            <a href="{{ route('pasien.show', $pasien->id) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom Dropdown Jenis Kelamin
    const btnGender = document.getElementById('btnDropdownGender');
    const menuGender = document.getElementById('menuDropdownGender');
    const inputGender = document.getElementById('inputJenisKelamin');
    const labelGender = document.getElementById('labelGender');

    window.toggleDropdownGender = function() {
        const isOpen = menuGender.classList.contains('show');
        if (isOpen) {
            menuGender.classList.remove('show');
            btnGender.classList.remove('open');
        } else {
            menuGender.classList.add('show');
            btnGender.classList.add('open');
        }
    };

    window.selectGender = function(val, text) {
        inputGender.value = val;
        labelGender.innerText = text;
        menuGender.classList.remove('show');
        btnGender.classList.remove('open');

        document.querySelectorAll('#menuDropdownGender .cd-item').forEach(item => {
            item.classList.remove('selected');
        });
        const selectedItem = document.querySelector(`#menuDropdownGender .cd-item[data-val="${val}"]`);
        if (selectedItem) selectedItem.classList.add('selected');
    };

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown-wrap')) {
            menuGender.classList.remove('show');
            btnGender.classList.remove('open');
        }
    });
});
</script>
@endsection
