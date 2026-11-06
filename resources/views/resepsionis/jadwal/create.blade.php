@extends('layouts.app')

@section('title', 'Tambah Jadwal - Eclair Beauty Clinic')

@section('extra_style')
.jadwal-card { max-width: 720px; margin: 0 auto; }
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
}
.form-control:focus { border-color: #C17B7B; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-error { color: #C0392B; font-size: 12px; margin-top: 4px; }

.jam-section-label {
    font-size: 12px;
    font-weight: 700;
    color: #9B9B9B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 20px 0 10px;
}
.jam-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.jam-chip {
    padding: 10px 6px;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 13.5px;
    color: #3A3A3A;
    transition: all 0.15s;
}
.jam-chip:hover { border-color: #C17B7B; background: #FBF1EC; }
.jam-chip input[type="radio"] { display: none; }
.jam-chip.selected { border-color: #C17B7B; background: #C17B7B; color: #fff; font-weight: 600; }
.jam-chip.disabled {
    background-color: #f0f0f0;
    color: #a0a0a0;
    cursor: not-allowed;
    border-color: #e0e0e0;
}
.jam-chip.disabled:hover { border-color: #e0e0e0; background: #f0f0f0; }

.form-actions { margin-top: 28px; display: flex; gap: 12px; }

/* Style Searchable Pasien */
.pasien-selected-card {
    display: none;
    padding: 11px 15px;
    background: #FAF3EF;
    border: 1.5px solid #C17B7B;
    border-radius: 10px;
    align-items: center;
    justify-content: space-between;
}
.pasien-selected-card.active { display: flex; }
.pasien-badge-id {
    background: #C17B7B;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    letter-spacing: 0.5px;
}
.pasien-dropdown {
    display: none;
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    margin-top: 4px;
    background: #ffffff;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.08);
    max-height: 240px;
    overflow-y: auto;
    z-index: 99;
}
.pasien-dropdown-item {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    border-bottom: 1px solid #F6EFEA;
    transition: background 0.15s;
}
.pasien-dropdown-item:last-child { border-bottom: none; }
.pasien-dropdown-item:hover { background: #FBF1EC; }
.pasien-item-id {
    font-size: 11px;
    font-weight: 700;
    color: #C17B7B;
    background: #FDF4F0;
    padding: 2px 7px;
    border-radius: 5px;
    margin-right: 8px;
}
.pasien-item-nama { font-size: 13.5px; font-weight: 600; color: #3A3A3A; }
.pasien-item-hp { font-size: 12px; color: #7A7A7A; }
.pasien-empty-item { padding: 14px; text-align: center; color: #9B9B9B; font-size: 13px; }
@endsection

@section('content')

<div class="page-header-row">
    <div>
        <h1 class="page-title">Tambah Jadwal Booking</h1>
        <p class="page-subtitle">Buat jadwal kunjungan baru untuk pasien walk-in</p>
    </div>
</div>

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="card jadwal-card">
    <form method="POST" action="{{ route('jadwal.store') }}">
        @csrf

        <div class="form-group" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label class="form-label" style="margin-bottom: 0;">Pilih Pasien *</label>
                <a href="{{ route('pasien.create') }}" target="_blank" style="font-size: 12px; color: #C17B7B; font-weight: 600; text-decoration: none;">+ Daftarkan Pasien Baru</a>
            </div>

            <!-- Hidden input untuk form submit -->
            <input type="hidden" name="pasien_id" id="selectedPasienId" value="{{ old('pasien_id') }}" required>

            <!-- Tampilan jika pasien sudah dipilih -->
            <div id="selectedPasienCard" class="pasien-selected-card">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span id="badgeSelectedId" class="pasien-badge-id">#0001</span>
                    <div>
                        <strong id="selectedNama" style="font-size: 14px; color: #3A3A3A;"></strong>
                        <span id="selectedHp" style="font-size: 12.5px; color: #6B6B6B; margin-left: 8px;"></span>
                    </div>
                </div>
                <button type="button" onclick="resetPilihPasien()" style="background: none; border: none; color: #C0392B; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: underline;">Ganti Pasien</button>
            </div>

            <!-- Kotak input pencarian (Bisa cari ID, Nama, atau No HP) -->
            <div id="searchPasienContainer">
                <div style="position: relative;">
                    <input type="text" id="inputSearchPasien" class="form-control" placeholder="Ketik ID Pasien (misal #001), Nama, atau No HP..." autocomplete="off">
                    <span style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9B9B9B; font-size: 14px; pointer-events: none;">🔍</span>
                </div>

                <!-- Dropdown hasil pencarian -->
                <div id="dropdownPasienList" class="pasien-dropdown">
                    <!-- Item akan di-render dinamis oleh JavaScript -->
                </div>
            </div>

            @error('pasien_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Pilih Dokter *</label>
            <select name="dokter_id" class="form-control" required>
                <option value="">-- Pilih Dokter --</option>
                @foreach($dokter as $d)
                    <option value="{{ $d->id }}" {{ old('dokter_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                @endforeach
            </select>
            @error('dokter_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tanggal Jadwal *</label>
                <input type="date" name="tanggal_jadwal" class="form-control"
                    value="{{ old('tanggal_jadwal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                @error('tanggal_jadwal') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control"
                    placeholder="Contoh: Konsultasi awal, Facial, dll" value="{{ old('keterangan') }}">
            </div>
        </div>

        <div class="jam-section-label">Pilih Jam Jadwal *</div>
        <div class="jam-grid">
            @php
                $jamList = ['09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00'];
            @endphp
            @foreach($jamList as $jam)
                <label class="jam-chip {{ old('jam_jadwal') == $jam ? 'selected' : '' }}" onclick="pilihJam(this)">
                    <input type="radio" name="jam_jadwal" value="{{ $jam }}" {{ old('jam_jadwal') == $jam ? 'checked' : '' }} required>
                    {{ $jam }}
                </label>
            @endforeach
        </div>
        @error('jam_jadwal') <div class="form-error">{{ $message }}</div> @enderror

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Jadwal</button>
            <a href="{{ route('jadwal.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function pilihJam(el) {
    if (el.classList.contains('disabled')) return;
    document.querySelectorAll('.jam-chip').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
}

document.addEventListener('DOMContentLoaded', function() {
    const dokterSelect = document.querySelector('select[name="dokter_id"]');
    const tanggalInput = document.querySelector('input[name="tanggal_jadwal"]');

    function checkJam() {
        const dokter_id = dokterSelect.value;
        const tanggal_jadwal = tanggalInput.value;

        if (!dokter_id || !tanggal_jadwal) return;

        fetch(`{{ route('jadwal.jam_terpakai') }}?dokter_id=${dokter_id}&tanggal_jadwal=${tanggal_jadwal}`)
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.jam-chip').forEach(chip => {
                    const input = chip.querySelector('input');
                    const jam = input.value;
                    
                    if (data.includes(jam)) {
                        chip.classList.add('disabled');
                        input.disabled = true;
                        // if currently selected, unselect it
                        if (chip.classList.contains('selected')) {
                            chip.classList.remove('selected');
                            input.checked = false;
                        }
                    } else {
                        chip.classList.remove('disabled');
                        input.disabled = false;
                    }
                });
            })
            .catch(error => console.error('Error fetching jam terpakai:', error));
    }

    dokterSelect.addEventListener('change', checkJam);
    tanggalInput.addEventListener('change', checkJam);

    // Initial check on load
    checkJam();

    // ==========================================
    // LOGIKA SEARCHABLE DROPDOWN PASIEN
    // (Bisa cari berdasarkan ID Pasien, Nama, atau No HP)
    // ==========================================
    const pasienList = @json($pasien->map(function($p) {
        return [
            'id' => $p->id,
            'nama' => $p->nama_pasien,
            'no_hp' => $p->no_hp ?? '-',
            'formatted_id' => '#' . str_pad($p->id, 4, '0', STR_PAD_LEFT)
        ];
    }));

    const inputSearch = document.getElementById('inputSearchPasien');
    const dropdown = document.getElementById('dropdownPasienList');
    const hiddenId = document.getElementById('selectedPasienId');
    const cardSelected = document.getElementById('selectedPasienCard');
    const searchContainer = document.getElementById('searchPasienContainer');
    const badgeId = document.getElementById('badgeSelectedId');
    const textNama = document.getElementById('selectedNama');
    const textHp = document.getElementById('selectedHp');

    function renderDropdown(keyword = '') {
        const q = keyword.trim().toLowerCase();
        const cleanQ = q.replace('#', '');

        const filtered = pasienList.filter(p => {
            const idMatch = p.id.toString().includes(cleanQ) || p.formatted_id.toLowerCase().includes(q);
            const namaMatch = p.nama.toLowerCase().includes(q);
            const hpMatch = p.no_hp.toLowerCase().includes(q);
            return idMatch || namaMatch || hpMatch;
        });

        if (filtered.length === 0) {
            dropdown.innerHTML = `
                <div class="pasien-empty-item">
                    Pasien tidak ditemukan.
                    <div style="margin-top: 6px;">
                        <a href="{{ route('pasien.create') }}" target="_blank" style="color: #C17B7B; font-weight: 700; text-decoration: none;">+ Daftarkan Pasien Baru</a>
                    </div>
                </div>`;
        } else {
            dropdown.innerHTML = filtered.map(p => `
                <div class="pasien-dropdown-item" onclick="pilihPasien(${p.id})">
                    <div style="display: flex; align-items: center;">
                        <span class="pasien-item-id">${p.formatted_id}</span>
                        <span class="pasien-item-nama">${p.nama}</span>
                    </div>
                    <span class="pasien-item-hp">📞 ${p.no_hp}</span>
                </div>
            `).join('');
        }
        dropdown.style.display = 'block';
    }

    window.pilihPasien = function(id) {
        const p = pasienList.find(x => x.id == id);
        if (!p) return;

        hiddenId.value = p.id;
        badgeId.innerText = p.formatted_id;
        textNama.innerText = p.nama;
        textHp.innerText = p.no_hp !== '-' ? '📞 ' + p.no_hp : '';

        dropdown.style.display = 'none';
        searchContainer.style.display = 'none';
        cardSelected.classList.add('active');
    };

    window.resetPilihPasien = function() {
        hiddenId.value = '';
        cardSelected.classList.remove('active');
        searchContainer.style.display = 'block';
        inputSearch.value = '';
        inputSearch.focus();
        renderDropdown('');
    };

    inputSearch.addEventListener('focus', function() {
        renderDropdown(inputSearch.value);
    });

    inputSearch.addEventListener('input', function(e) {
        renderDropdown(e.target.value);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#searchPasienContainer')) {
            dropdown.style.display = 'none';
        }
    });

    // Inisialisasi jika ada nilai awal dari old('pasien_id')
    const initialId = "{{ old('pasien_id') }}";
    if (initialId) {
        pilihPasien(initialId);
    }
});
</script>

@endsection
