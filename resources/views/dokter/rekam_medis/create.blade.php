@extends('layouts.app')

@section('title', 'Input Rekam Medis - Eclair Beauty Clinic')

@section('extra_style')
    .breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; color: #9B9B9B;
    text-transform: uppercase; letter-spacing: 0.5px;
    margin-bottom: 6px;
    }
    .breadcrumb a { color: #9B9B9B; text-decoration: none; }
    .breadcrumb a:hover { color: #C17B7B; }
    .breadcrumb .bc-sep { color: #D0C8C0; }
    .breadcrumb .bc-active { color: #C17B7B; font-weight: 600; }

    .form-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 24px;
    align-items: start;
    }

    .card-title {
    font-size: 15px; font-weight: 700; color: #3A3A3A;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
    }
    .card-title svg { color: #C17B7B; }

    .form-group { margin-bottom: 18px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
    display: block;
    font-size: 12px; font-weight: 600;
    color: #6B6B6B;
    text-transform: uppercase; letter-spacing: 0.4px;
    margin-bottom: 7px;
    }

    .form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #EDE5DF;
    border-radius: 10px;
    font-size: 13.5px;
    color: #3A3A3A;
    background: #FDFAF8;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    font-family: inherit;
    }
    .form-control:focus { border-color: #C17B7B; background: #fff; }
    .form-control::placeholder { color: #C0B8B2; }

    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Foto upload area */
    .foto-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 4px; }
    .foto-upload-area {
    border: 2px dashed #EDE5DF;
    border-radius: 12px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    background: #FDFAF8;
    }
    .foto-upload-area:hover { border-color: #C17B7B; background: #FBF1EC; }
    .foto-upload-area input[type="file"] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .foto-icon {
    width: 40px; height: 40px;
    background: #F0E8E2;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px;
    color: #C17B7B;
    }
    .foto-label-text { font-size: 12px; color: #9B9B9B; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;
    }
    .foto-sublabel { font-size: 11px; color: #C0B8B2; margin-top: 4px; }
    .foto-preview-img {
    width: 100%; height: 140px;
    object-fit: cover;
    border-radius: 10px;
    display: none;
    margin-top: 8px;
    }

    /* Right panel - kontrol & status */
    .right-panel { display: flex; flex-direction: column; gap: 20px; }

    .status-option {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    border: 2px solid #F0E8E2;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 10px;
    }
    .status-option:last-child { margin-bottom: 0; }
    .status-option:hover { border-color: #C17B7B; background: #FBF1EC; }
    .status-option.selected { border-color: #C17B7B; background: #FBF1EC; }

    .status-option input[type="radio"] { display: none; }

    .status-icon-circle {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
    }
    .status-icon-green { background: #E2F5E8; color: #2E8B4F; }
    .status-icon-blue { background: #DCEEF5; color: #2C7A9B; }
    .status-icon-gray { background: #F0F0F0; color: #888; }

    .status-opt-title { font-size: 13.5px; font-weight: 700; color: #3A3A3A; }
    .status-opt-desc { font-size: 12px; color: #9B9B9B; margin-top: 3px; line-height: 1.4; }

    /* Form action buttons */
    .form-actions {
    display: flex; justify-content: flex-end; gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #F0E8E2;
    }
    .btn-batal-form {
    padding: 11px 24px;
    background: white;
    color: #6B6B6B;
    border: 1px solid #E8DDD5;
    border-radius: 10px;
    font-size: 14px; font-weight: 500;
    cursor: pointer; text-decoration: none;
    transition: all 0.2s;
    }
    .btn-batal-form:hover { background: #FAF6F2; }

    .btn-simpan-form {
    padding: 11px 28px;
    background: #C17B7B; color: white;
    border: none; border-radius: 10px;
    font-size: 14px; font-weight: 600;
    cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    transition: background 0.2s;
    }
    .btn-simpan-form:hover { background: #B06B6B; }
@endsection

@section('content')

    <div class="breadcrumb">
        <a href="{{ route('dokter.dashboard') }}">Manajemen</a>
        <span class="bc-sep">›</span>
        <a href="{{ route('rekam_medis.index') }}">Rekam Medis</a>
        <span class="bc-sep">›</span>
        <span class="bc-active">Input Tindakan</span>
    </div>

    <div class="page-header-row" style="margin-bottom:24px;">
        <div>
            <h1 class="page-title">Input Rekam Medis</h1>
            <p class="page-subtitle" style="margin-bottom:0;">
                Pasien: <strong>{{ $pasien->nama_pasien }}</strong>
                <span style="color:#C17B7B; font-weight:600;">(#EC{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }})</span>
            </p>
        </div>
        <div
            style="font-size:13px; color:#6B6B6B; display:flex; align-items:center; gap:8px; background:#FAF6F2; padding:10px 18px; border-radius:12px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM YYYY') }}
        </div>
    </div>

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong>Form belum lengkap:</strong>
            <ul style="margin:6px 0 0 18px; padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rekam_medis.store', $pasien->id) }}" enctype="multipart/form-data"
        id="formRekamMedis">
        @csrf
        <input type="hidden" name="status_kunjungan" id="statusKunjungan" value="selesai">

        <div class="form-grid">

            {{-- KIRI: Detail Tindakan --}}
            <div>
                <div class="card" style="padding:24px;">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        Detail Tindakan
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Tindakan <span style="color:#C17B7B;">*</span></label>
                        <input type="date" name="tanggal_tindakan" class="form-control"
                            value="{{ old('tanggal_tindakan', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keluhan Pasien</label>
                        <textarea name="keluhan" class="form-control" rows="3" placeholder="Masukkan keluhan pasien...">{{ old('keluhan') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosa</label>
                        <textarea name="hasil_konsultasi" class="form-control" rows="3" placeholder="Diagnosa dokter...">{{ old('hasil_konsultasi') }}</textarea>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Jenis Perawatan</label>
                            <select name="jenis_perawatan" class="form-control">
                                <option value="">Pilih jenis perawatan</option>
                                <option value="Facial Glow"
                                    {{ old('jenis_perawatan') == 'Facial Glow' ? 'selected' : '' }}>Facial Glow</option>
                                <option value="Chemical Peeling"
                                    {{ old('jenis_perawatan') == 'Chemical Peeling' ? 'selected' : '' }}>Chemical Peeling
                                </option>
                                <option value="Laser Therapy"
                                    {{ old('jenis_perawatan') == 'Laser Therapy' ? 'selected' : '' }}>Laser Therapy
                                </option>
                                <option value="Botox" {{ old('jenis_perawatan') == 'Botox' ? 'selected' : '' }}>Botox
                                </option>
                                <option value="Konsultasi" {{ old('jenis_perawatan') == 'Konsultasi' ? 'selected' : '' }}>
                                    Konsultasi</option>
                                <option value="Lainnya" {{ old('jenis_perawatan') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Produk Digunakan</label>
                            <input type="text" name="catatan_tindakan" class="form-control"
                                placeholder="Nama produk/obat..." value="{{ old('catatan_tindakan') }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Foto Sebelum &amp; Sesudah</label>
                        <div class="foto-grid">
                            <div>
                                <div class="foto-upload-area" onclick="this.querySelector('input').click()">
                                    <input type="file" name="foto_before" accept="image/*"
                                        onchange="previewFoto(this, 'prev-before', this.closest('.foto-upload-area'))">
                                    <div class="foto-icon">
                                        <svg width="20" height="20" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </div>
                                    <div class="foto-label-text">Foto Sebelum</div>
                                    <div class="foto-sublabel">JPG, PNG maks 2MB</div>
                                </div>
                                <img id="prev-before" class="foto-preview-img">
                            </div>
                            <div>
                                <div class="foto-upload-area" onclick="this.querySelector('input').click()">
                                    <input type="file" name="foto_after" accept="image/*"
                                        onchange="previewFoto(this, 'prev-after', this.closest('.foto-upload-area'))">
                                    <div class="foto-icon">
                                        <svg width="20" height="20" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </div>
                                    <div class="foto-label-text">Foto Sesudah</div>
                                    <div class="foto-sublabel">JPG, PNG maks 2MB</div>
                                </div>
                                <img id="prev-after" class="foto-preview-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: Kontrol Lanjutan + Status --}}
            <div class="right-panel">

                {{-- Rencana Kontrol Lanjutan --}}
                <div class="card" style="padding:24px;">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Rencana Kontrol Lanjutan
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Kontrol</label>
                        <input type="date" name="tanggal_kontrol" class="form-control"
                            value="{{ old('tanggal_kontrol') }}" min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jam Kontrol</label>
                        <input type="time" name="jam_kontrol" class="form-control" value="{{ old('jam_kontrol') }}">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Instruksi Dokter / Catatan</label>
                        <textarea name="keterangan_foto" class="form-control" rows="4" placeholder="Catatan Dokter">{{ old('keterangan_foto') }}</textarea>
                    </div>
                </div>

                {{-- Status Akhir Kunjungan --}}
                <div class="card" style="padding:24px;">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        Status Akhir Kunjungan
                    </div>
                    <p
                        style="font-size:12px; color:#9B9B9B; margin-bottom:16px; text-transform:uppercase; letter-spacing:0.3px;">
                        Pilih status kepulangan pasien</p>

                    <label class="status-option selected" id="opt-selesai">
                        <input type="radio" name="_status_kunjungan" value="selesai" checked
                            onchange="setStatus('selesai')">
                        <div class="status-icon-circle status-icon-green">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <div>
                            <div class="status-opt-title">Selesai (Rawat Jalan)</div>
                            <div class="status-opt-desc">Pasien selesai tindakan dan diperbolehkan pulang hari ini.</div>
                        </div>
                    </label>

                    <label class="status-option" id="opt-rujuk">
                        <input type="radio" name="_status_kunjungan" value="rujuk" onchange="setStatus('rujuk')">
                        <div class="status-icon-circle status-icon-blue">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                        </div>
                        <div>
                            <div class="status-opt-title">Rujuk ke RS / Spesialis Lain</div>
                            <div class="status-opt-desc">Pasien membutuhkan penanganan lanjutan di fasilitas lain.</div>
                        </div>
                    </label>

                    <label class="status-option" id="opt-meninggal">
                        <input type="radio" name="_status_kunjungan" value="meninggal"
                            onchange="setStatus('meninggal')">
                        <div class="status-icon-circle status-icon-gray">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <div>
                            <div class="status-opt-title">Meninggal Dunia</div>
                            <div class="status-opt-desc">Pasien dinyatakan meninggal dunia selama dalam perawatan.</div>
                        </div>
                    </label>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="form-actions" style="margin-top:8px; background:white; border-radius:16px; margin-top:20px;">
            <a href="{{ route('rekam_medis.show', $pasien->id) }}" class="btn-batal-form">Batal</a>
            <button type="submit" class="btn-simpan-form">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                Simpan Rekam Medis
            </button>
        </div>

    </form>

    <script>
        function previewFoto(input, previewId, area) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    area.style.borderStyle = 'solid';
                    area.style.borderColor = '#C17B7B';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function setStatus(val) {
            document.getElementById('statusKunjungan').value = val;
            ['selesai', 'rujuk', 'meninggal'].forEach(function(k) {
                document.getElementById('opt-' + k).classList.toggle('selected', k === val);
            });
        }

        // Prevent double file input click
        document.querySelectorAll('.foto-upload-area').forEach(function(area) {
            area.addEventListener('click', function(e) {
                if (e.target.tagName === 'INPUT') return;
                area.querySelector('input[type="file"]').click();
            });
        });
    </script>

@endsection
