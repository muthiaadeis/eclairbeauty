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
    padding: 0.6rem 0.85rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.875rem;
    color: #374151;
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
    font-size: 0.85rem;
    color: #374151;
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

/* ── Custom Flatpickr Kalender Klinik ── */
.flatpickr-calendar {
    position: absolute;
    display: none;
    background: #FFF !important;
    border: 1px solid #F0E8E2 !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    font-family: inherit !important;
    padding: 10px !important;
    width: 310px !important;
    z-index: 99999 !important;
}
.flatpickr-calendar.open {
    display: inline-block !important;
    z-index: 99999 !important;
}
.flatpickr-calendar::before, .flatpickr-calendar::after { display: none !important; }
.flatpickr-months {
    background: #FAF6F2 !important;
    border-radius: 10px !important;
    padding: 6px 8px !important;
    display: flex !important;
    align-items: center !important;
    position: relative !important;
}
.flatpickr-current-month {
    padding: 0 !important;
    font-size: 0.95rem !important;
    line-height: inherit !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
    width: 100% !important;
    position: static !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months {
    font-weight: 600 !important;
    color: #3A3A3A !important;
    font-size: 0.9rem !important;
    background: transparent !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 2px 6px !important;
    cursor: pointer !important;
    outline: none !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months:hover {
    background: rgba(193,123,123,0.15) !important;
    color: #C17B7B !important;
}
.flatpickr-current-month .numInputWrapper {
    width: 65px !important;
}
.flatpickr-current-month input.cur-year {
    font-weight: 600 !important;
    color: #3A3A3A !important;
    font-size: 0.9rem !important;
    padding: 2px 4px !important;
}
.flatpickr-prev-month, .flatpickr-next-month {
    padding: 6px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.flatpickr-prev-month svg, .flatpickr-next-month svg {
    fill: #C17B7B !important;
    width: 13px !important;
    height: 13px !important;
}
.flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg {
    fill: #B06B6B !important;
}
.flatpickr-weekdays {
    margin-top: 8px !important;
}
span.flatpickr-weekday {
    color: #9CA3AF !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
}
.flatpickr-days { width: 100% !important; }
.dayContainer { width: 100% !important; min-width: 100% !important; max-width: 100% !important; justify-content: space-around !important; }
.flatpickr-day {
    border-radius: 10px !important;
    color: #374151 !important;
    height: 36px !important;
    line-height: 36px !important;
    max-width: 38px !important;
    font-size: 0.85rem !important;
    transition: all 0.15s !important;
}
.flatpickr-day:hover {
    background: #FBF1EC !important;
    border-color: #FBF1EC !important;
    color: #C17B7B !important;
}
.flatpickr-day.selected, .flatpickr-day.selected:hover {
    background: #C17B7B !important;
    border-color: #C17B7B !important;
    color: #FFF !important;
    font-weight: 600 !important;
}
.flatpickr-day.today {
    border-color: #C17B7B !important;
    color: #C17B7B !important;
}
.flatpickr-day.today:hover {
    background: #C17B7B !important;
    color: #FFF !important;
}
.flatpickr-input[readonly] {
    cursor: pointer !important;
    background-color: #FFF !important;
}
.date-input-wrap {
    position: relative;
}
.date-input-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #9CA3AF;
}
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('resepsionis.dashboard') }}">Manajemen</a>
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    @if(request('from') === 'jadwal')
        <a href="{{ route('jadwal.create') }}">Tambah Jadwal</a>
    @else
        <a href="{{ route('pasien.index') }}">Data Pasien</a>
    @endif
    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <span class="bc-active">Tambah Pasien</span>
</div>

<h1 class="page-title">Tambah Pasien Baru</h1>
<p class="page-subtitle">Lengkapi formulir di bawah ini untuk mendaftarkan pasien baru ke dalam sistem.</p>

<form method="POST" action="{{ route('pasien.store') }}">
@csrf
@if(request('from'))
    <input type="hidden" name="from" value="{{ request('from') }}">
@endif
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
                <input type="hidden" name="jenis_kelamin" id="inputJenisKelamin" value="{{ old('jenis_kelamin') }}">
                <div class="custom-dropdown-wrap">
                    <button type="button" class="custom-dropdown-btn" id="btnDropdownGender" onclick="toggleDropdownGender()">
                        <span id="labelGender">{{ old('jenis_kelamin') == 'P' ? 'Perempuan' : (old('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Pilih jenis kelamin') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="custom-dropdown-menu" id="menuDropdownGender">
                        <button type="button" class="cd-item {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}" data-val="P" onclick="selectGender('P', 'Perempuan')">
                            <span>Perempuan</span>
                            <svg class="cd-check" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </button>
                        <button type="button" class="cd-item {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}" data-val="L" onclick="selectGender('L', 'Laki-laki')">
                            <span>Laki-laki</span>
                            <svg class="cd-check" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </button>
                    </div>
                </div>
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
                <div class="date-input-wrap">
                    <input type="text" id="tanggalLahirPicker" name="tanggal_lahir" class="fc"
                           placeholder="Pilih tanggal lahir"
                           value="{{ old('tanggal_lahir') }}" readonly>
                    <span class="date-input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                </div>
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
        <a href="{{ request('from') === 'jadwal' ? route('jadwal.create') : route('pasien.index') }}" class="btn-cancel">Batal</a>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Flatpickr Kalender Estetik Klinik Eclair
    flatpickr("#tanggalLahirPicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        altInputClass: "fc",
        locale: "id",
        maxDate: "today",
        allowInput: false,
        @if(old('tanggal_lahir'))
        defaultDate: "{{ old('tanggal_lahir') }}",
        @endif
        disableMobile: true
    });

    // 2. Custom Dropdown Jenis Kelamin
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
