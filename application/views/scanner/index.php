<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Pemindai Kamera QR Code Fisik</h2>
                <p class="text-muted small mb-0">Arahkan kamera ke QR kardus lot/batch atau ketik kode barcode secara manual.</p>
            </div>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 fw-semibold">
                <i class="bi bi-camera-video me-1"></i> Live Scanner
            </span>
        </div>

        <div class="mb-card p-4 mb-4">
            <!-- Manual input bar for handheld scanner or manual typing -->
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                <input type="text" id="manual-code-input" class="form-control" placeholder="Tempel / ketik kode Batch atau SKU lalu tekan Enter..." autofocus>
                <button type="button" class="btn btn-primary" onclick="submitManualCode()">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>

            <!-- Video Camera Viewport -->
            <div class="position-relative bg-dark rounded-3 overflow-hidden text-center" style="min-height: 280px; max-height: 400px;">
                <div id="qr-reader" style="width: 100%;"></div>
                <div id="camera-placeholder" class="py-5 text-white-50">
                    <i class="bi bi-camera fs-1 d-block mb-2"></i>
                    <p class="mb-2">Kamera siap diaktifkan</p>
                    <button type="button" id="btn-start-camera" class="btn btn-sm btn-light fw-semibold" onclick="startCamera()">
                        <i class="bi bi-play-circle me-1"></i> Aktifkan Kamera
                    </button>
                </div>
            </div>
        </div>

        <!-- Scan Result Card -->
        <div class="mb-card p-4 d-none" id="scan-result-card">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded bg-success-subtle text-success">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="result-title">Item Ditemukan</h5>
                        <small class="text-muted" id="result-subtitle">Data sinkron dari basis data</small>
                    </div>
                </div>
                <span class="badge bg-success" id="result-type-badge">BATCH</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="text-muted small">Nama Produk:</div>
                    <div class="fw-bold fs-6" id="result-nama-produk">-</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Nomor Lot / Batch / SKU:</div>
                    <div class="fw-bold font-monospace text-primary" id="result-code">-</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Sisa Stok Fisik:</div>
                    <div class="fw-bold fs-5 text-success tabular-nums" id="result-stock">-</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Tanggal Masuk Gudang (FIFO):</div>
                    <div class="fw-semibold" id="result-exp">-</div>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <a href="#" id="btn-action-masuk" class="btn btn-outline-success d-inline-flex align-items-center justify-content-center py-2 px-3 fw-semibold flex-fill flex-sm-grow-0">
                    <i class="bi bi-arrow-down-left me-1.5"></i> Catat Stok Masuk
                </a>
                <a href="#" id="btn-action-keluar" class="btn btn-danger d-inline-flex align-items-center justify-content-center py-2 px-3 fw-semibold flex-fill flex-sm-grow-0" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                    <i class="bi bi-arrow-up-right me-1.5"></i> Catat Stok Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- html5-qrcode CDN -->
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrCode = null;
const soundEffectsEnabled = <?= $sound_effects ? 'true' : 'false'; ?>;

function playBeep() {
    if (!soundEffectsEnabled) return;
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, audioCtx.currentTime); // 880Hz A5
        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.15);
    } catch (e) {
        console.warn('Audio Context not available', e);
    }
}

document.getElementById('manual-code-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        submitManualCode();
    }
});

function submitManualCode() {
    const code = document.getElementById('manual-code-input').value.trim();
    if (!code) return;
    processScannedCode(code);
}

function startCamera() {
    document.getElementById('camera-placeholder').classList.add('d-none');
    html5QrCode = new Html5Qrcode("qr-reader");

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            playBeep();
            processScannedCode(decodedText);
            html5QrCode.stop().then(() => {
                document.getElementById('camera-placeholder').classList.remove('d-none');
            }).catch(() => {});
        },
        (errorMessage) => {}
    ).catch(err => {
        alert("Gagal mengakses kamera: " + err);
        document.getElementById('camera-placeholder').classList.remove('d-none');
    });
}

function processScannedCode(code) {
    fetch(window.BASE_URL + 'api/batch/scan/' + encodeURIComponent(code))
        .then(res => res.json())
        .then(res => {
            if (!res.status || !res.data) {
                alert(res.message || 'Kode tidak ditemukan dalam katalog.');
                return;
            }

            playBeep();
            const card = document.getElementById('scan-result-card');
            card.classList.remove('d-none');

            if (res.type === 'batch') {
                document.getElementById('result-type-badge').textContent = 'BATCH KARDUS';
                document.getElementById('result-nama-produk').textContent = res.data.nama_produk || '-';
                document.getElementById('result-code').textContent = res.data.nomor_batch;
                document.getElementById('result-stock').textContent = Number(res.data.stok_saat_ini).toLocaleString() + ' unit';
                document.getElementById('result-exp').textContent = (res.data.tanggal_masuk || '-') + (res.data.lokasi_rak ? ' · Rak: ' + res.data.lokasi_rak : '');

                document.getElementById('btn-action-masuk').href = window.BASE_URL + 'stok/masuk?batch_id=' + res.data.id;
                document.getElementById('btn-action-keluar').href = window.BASE_URL + 'stok/keluar?batch_id=' + res.data.id;
            } else {
                document.getElementById('result-type-badge').textContent = 'MASTER PRODUK';
                document.getElementById('result-nama-produk').textContent = res.data.nama_produk;
                document.getElementById('result-code').textContent = res.data.sku;
                document.getElementById('result-stock').textContent = Number(res.data.stok).toLocaleString() + ' unit';
                document.getElementById('result-exp').textContent = '-';

                document.getElementById('btn-action-masuk').href = window.BASE_URL + 'stok/masuk?produk_id=' + res.data.id;
                document.getElementById('btn-action-keluar').href = window.BASE_URL + 'stok/keluar?produk_id=' + res.data.id;
            }

            card.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(err => {
            alert('Terjadi kesalahan menghubungi server.');
        });
}
</script>
