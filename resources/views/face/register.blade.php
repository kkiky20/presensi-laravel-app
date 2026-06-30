@extends('layouts.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Daftarkan Wajah</div>
        <div class="right"></div>
    </div>

    <style>
        .face-register-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .face-register-container video {
            width: 100%;
            height: auto;
            border-radius: 16px;
            transform: scaleX(-1);
        }

        .face-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            border-radius: 16px;
        }

        .face-overlay svg {
            width: 100%;
            height: 100%;
        }

        .status-panel {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 14px;
            padding: 16px;
            margin: 12px 0;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .status-icon.loading {
            background: rgba(255,255,255,0.3);
            animation: pulse 1.5s infinite;
        }

        .status-icon.success {
            background: #00c853;
        }

        .status-icon.error {
            background: #ff5252;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .guide-card {
            background: white;
            border-radius: 14px;
            padding: 16px;
            margin: 12px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .guide-card h4 {
            font-size: 0.95rem;
            margin-bottom: 10px;
            color: #333;
        }

        .guide-steps {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .guide-step {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #555;
        }

        .guide-step .step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        #btnSaveFace {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        #btnSaveFace:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
        }

        #btnSaveFace:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .face-detected-border {
            border: 3px solid #00c853;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0, 200, 83, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="row" style="margin-top: 60px;">
        <div class="col" style="padding: 0 16px;">

            {{-- Guide Card --}}
            <div class="guide-card">
                <h4>📸 Panduan Pendaftaran Wajah</h4>
                <div class="guide-steps">
                    <div class="guide-step">
                        <span class="step-num">1</span>
                        <span>Posisikan wajah Anda di dalam bingkai oval</span>
                    </div>
                    <div class="guide-step">
                        <span class="step-num">2</span>
                        <span>Pastikan pencahayaan cukup dan wajah terlihat jelas</span>
                    </div>
                    <div class="guide-step">
                        <span class="step-num">3</span>
                        <span>Tunggu hingga wajah terdeteksi, lalu klik Simpan</span>
                    </div>
                </div>
            </div>

            {{-- Camera Feed --}}
            <div class="face-register-container" id="cameraContainer">
                <video id="video" autoplay playsinline muted></video>
                <div class="face-overlay">
                    <svg viewBox="0 0 400 300" preserveAspectRatio="none">
                        <defs>
                            <mask id="ovalMask">
                                <rect width="400" height="300" fill="white"/>
                                <ellipse cx="200" cy="140" rx="90" ry="115" fill="black"/>
                            </mask>
                        </defs>
                        <rect width="400" height="300" fill="rgba(0,0,0,0.4)" mask="url(#ovalMask)"/>
                        <ellipse cx="200" cy="140" rx="90" ry="115"
                                 fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-dasharray="8,4"
                                 id="ovalGuide"/>
                    </svg>
                </div>
            </div>

            {{-- Status Panel --}}
            <div class="status-panel">
                <div class="status-item" id="statusModel">
                    <span class="status-icon loading">⏳</span>
                    <span>Memuat model AI...</span>
                </div>
                <div class="status-item" id="statusCamera">
                    <span class="status-icon loading">⏳</span>
                    <span>Menunggu kamera...</span>
                </div>
                <div class="status-item" id="statusFace">
                    <span class="status-icon loading">⏳</span>
                    <span>Mendeteksi wajah...</span>
                </div>
            </div>

            {{-- Save Button --}}
            <button id="btnSaveFace" disabled>
                <ion-icon name="scan-outline"></ion-icon> Simpan Data Wajah
            </button>

            <p style="text-align:center; color:#999; font-size:0.8rem; margin-top:12px; margin-bottom:100px;">
                Data wajah Anda akan disimpan secara aman dan hanya digunakan untuk verifikasi presensi.
            </p>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    const video = document.getElementById('video');
    const btnSave = document.getElementById('btnSaveFace');
    const cameraContainer = document.getElementById('cameraContainer');
    const ovalGuide = document.getElementById('ovalGuide');
    let faceDescriptor = null;
    let isModelLoaded = false;
    let detectionInterval = null;

    // Status update helpers
    function updateStatus(elementId, status, text) {
        const el = document.getElementById(elementId);
        const icon = el.querySelector('.status-icon');
        const span = el.querySelector('span:last-child');
        icon.className = 'status-icon ' + status;
        if (status === 'success') icon.textContent = '✅';
        else if (status === 'error') icon.textContent = '❌';
        else icon.textContent = '⏳';
        span.textContent = text;
    }

    // Load face-api models
    async function loadModels() {
        try {
            const MODEL_URL = "{{ asset('face-api/js/models') }}";
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            isModelLoaded = true;
            updateStatus('statusModel', 'success', 'Model AI siap!');
            startCamera();
        } catch (err) {
            console.error('Error loading models:', err);
            updateStatus('statusModel', 'error', 'Gagal memuat model AI');
        }
    }

    // Start camera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
        }).then(stream => {
            video.srcObject = stream;
            updateStatus('statusCamera', 'success', 'Kamera aktif!');

            video.addEventListener('playing', () => {
                startDetection();
            }, { once: true });
        }).catch(err => {
            console.error('Camera error:', err);
            updateStatus('statusCamera', 'error', 'Gagal mengakses kamera');
        });
    }

    // Start face detection loop
    function startDetection() {
        updateStatus('statusFace', 'loading', 'Mendeteksi wajah...');

        detectionInterval = setInterval(async () => {
            if (!isModelLoaded || video.paused || video.ended) return;

            try {
                const result = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 320,
                        scoreThreshold: 0.5
                    }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (result) {
                    faceDescriptor = Array.from(result.descriptor);
                    updateStatus('statusFace', 'success', 'Wajah terdeteksi! Siap disimpan.');
                    btnSave.disabled = false;
                    cameraContainer.classList.add('face-detected-border');
                    ovalGuide.setAttribute('stroke', '#00c853');
                    ovalGuide.setAttribute('stroke-width', '3');
                } else {
                    faceDescriptor = null;
                    updateStatus('statusFace', 'loading', 'Mendeteksi wajah...');
                    btnSave.disabled = true;
                    cameraContainer.classList.remove('face-detected-border');
                    ovalGuide.setAttribute('stroke', 'rgba(255,255,255,0.8)');
                    ovalGuide.setAttribute('stroke-width', '2');
                }
            } catch (err) {
                console.error('Detection error:', err);
            }
        }, 500);
    }

    // Save face descriptor
    btnSave.addEventListener('click', function() {
        if (!faceDescriptor) {
            Swal.fire('Error', 'Wajah belum terdeteksi!', 'error');
            return;
        }

        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

        fetch('/face/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                descriptor: JSON.stringify(faceDescriptor)
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Stop camera
                if (detectionInterval) clearInterval(detectionInterval);
                const stream = video.srcObject;
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                Swal.fire({
                    title: 'Berhasil! 🎉',
                    text: 'Data wajah Anda berhasil didaftarkan.',
                    icon: 'success',
                    confirmButtonColor: '#6f42c1',
                    confirmButtonText: 'Kembali ke Dashboard'
                }).then(() => {
                    window.location.href = '/dashboard';
                });
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                btnSave.disabled = false;
                btnSave.innerHTML = '<ion-icon name="scan-outline"></ion-icon> Simpan Data Wajah';
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            Swal.fire('Error', 'Gagal menyimpan data wajah.', 'error');
            btnSave.disabled = false;
            btnSave.innerHTML = '<ion-icon name="scan-outline"></ion-icon> Simpan Data Wajah';
        });
    });

    // Initialize
    loadModels();
</script>
@endpush
