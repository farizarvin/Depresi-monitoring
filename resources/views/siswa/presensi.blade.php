@extends('layouts.siswa')

@section('title', 'Absensi - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Presensi Siswa';
    $pageSubtitle = 'Catat kehadiran dan kelola riwayat absensi Anda';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/presensi.css') }}">
@endsection

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">Form Absensi Hari Ini</h2>
            <p class="form-date" id="currentDate">Minggu, 16 November 2025</p>
        </div>

        <form id="absensiForm">
            {{-- Status Selection Component --}}
            @include('components.presensi.status-selection')

            {{-- Form Izin Component --}}
            @include('components.presensi.form-izin')

            {{-- Form Sakit Component --}}
            @include('components.presensi.form-sakit')

            {{-- Webcam Section --}}
            <div class="upload-section">
                <label class="form-label">Ambil Foto Selfie</label>
                <div class="webcam-container text-center">
                    <div id="cameraArea" class="mb-3">
                        <video id="webcam" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 10px; display: none;"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div id="placeholder" class="p-5 bg-light rounded border" style="cursor: pointer;">
                            <i class="bi bi-camera-fill fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Klik untuk aktifkan kamera</p>
                        </div>
                    </div>
                    
                    <div id="previewArea" style="display: none;" class="mb-3 position-relative">
                        <img id="photoPreview" src="" class="img-fluid rounded" style="width: 100%; max-width: 400px;">
                        <button type="button" id="retakeBtn" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Foto Ulang
                        </button>
                    </div>

                    <button type="button" id="captureBtn" class="btn btn-primary w-100" style="display: none;">
                        <i class="bi bi-camera"></i> Ambil Foto
                    </button>
                </div>
                <input type="file" id="swafotoInput" name="swafoto" class="d-none" accept="image/*">
            </div>

            {{-- Mood Form Component --}}
            @include('components.presensi.mood-form')

            {{-- Hidden Inputs for Prediction --}}
            <input type="hidden" name="swafoto_pred" id="swafotoPred">
            <input type="hidden" name="catatan_pred" id="catatanPred">
            <input type="hidden" name="catatan_ket" id="catatanKet">

            <button type="submit" class="btn-submit">Kirim Absensi</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Status Change Handler
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const formIzin = document.getElementById('formIzin');
    const formSakit = document.getElementById('formSakit');

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            formIzin.classList.remove('show');
            formSakit.classList.remove('show');

            if (this.value === 'I') {
                formIzin.classList.add('show');
            } else if (this.value === 'S') {
                formSakit.classList.add('show');
            }
        });
    });

    // Webcam Elements
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const placeholder = document.getElementById('placeholder');
    const captureBtn = document.getElementById('captureBtn');
    const previewArea = document.getElementById('previewArea');
    const photoPreview = document.getElementById('photoPreview');
    const retakeBtn = document.getElementById('retakeBtn');
    const swafotoInput = document.getElementById('swafotoInput');
    const cameraArea = document.getElementById('cameraArea');

    let stream = null;

    // Initialize Camera
    placeholder.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            captureBtn.style.display = 'block';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    });

    // Retake Photo
    retakeBtn.addEventListener('click', async () => {
        // Hide preview, show camera again
        previewArea.style.display = 'none';
        placeholder.style.display = 'block';
        swafotoInput.value = ''; // Clear file input
        
        // Restart camera
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            captureBtn.style.display = 'block';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    });

    // API Config
    const API_FACE_URL = "https://risetkami-risetkami.hf.space/predict_face";
    const API_TEXT_URL = "https://risetkami-risetkami.hf.space/predict_text";

    // Prediction Functions
    async function predictFace(file) {
        console.log('🖼️ Starting Face Prediction...');
        const formData = new FormData();
        formData.append('file', file);
        
        try {
            const response = await fetch(API_FACE_URL, {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            
            console.log('✅ FACE PREDICTION RESULT:');
            console.log(JSON.stringify(res, null, 2));
            
            const data = res.response;
            document.getElementById('swafotoPred').value = JSON.stringify(data);
            return data;
        } catch (error) {
            console.error('❌ Face Prediction Error:', error);
            document.getElementById('swafotoPred').value = JSON.stringify({ error: error.message });
            return null;
        }
    }

    async function predictText(text) {
        console.log('📝 Starting Text Prediction...');
        console.log('Input text:', text);
        
        try {
            const response = await fetch(API_TEXT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ text: text })
            });
            const res = await response.json();
            
            console.log('✅ TEXT PREDICTION RESULT:');
            console.log(JSON.stringify(res, null, 2));
            
            const data = res.response;
            document.getElementById('catatanPred').value = JSON.stringify(data);
            // catatan_ket will be set from textarea during form submit 
            return data;
        } catch (error) {
            console.error('❌ Text Prediction Error:', error);
            document.getElementById('catatanPred').value = JSON.stringify({ error: error.message });
            return null;
        }
    }

    // Capture Photo with Prediction
    captureBtn.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        
        // Convert to file
        canvas.toBlob(async blob => {
            const file = new File([blob], "swafoto.jpg", { type: "image/jpeg" });
            
            // Create FileList hack for input file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            swafotoInput.files = dataTransfer.files;

            // Show preview
            photoPreview.src = URL.createObjectURL(blob);
            previewArea.style.display = 'block';
            video.style.display = 'none';
            captureBtn.style.display = 'none';
            
            // Stop stream
            stream.getTracks().forEach(track => track.stop());

            // Trigger Face Prediction immediately
            console.log('📸 Photo captured! Running prediction...');
            await predictFace(file);

        }, 'image/jpeg');
    });

    // Form Submit with Text Prediction
    document.getElementById('absensiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const status = document.querySelector('input[name="status"]:checked');
        
        if (!status) {
            alert('Mohon pilih status kehadiran!');
            return;
        }

        if (status.value === 'I') {
            const alasanIzin = document.getElementById('alasanIzin').value;
            if (!alasanIzin) {
                alert('Mohon isi alasan izin!');
                return;
            }
        }

        if (status.value === 'S') {
            const jenisSakit = document.getElementById('jenisSakit').value;
            if (!jenisSakit) {
                alert('Mohon isi jenis sakit!');
                return;
            }
        }

        // Show loading state
        const submitBtn = document.querySelector('.btn-submit');
        const originalBtnText = submitBtn.innerText;
        submitBtn.innerText = 'Memproses...';
        submitBtn.disabled = true;

        try {
            // Trigger Text Prediction
            const catatan = document.querySelector('textarea[name="catatan"]').value;
            // if (catatan) {
            //     await predictText(catatan);
            // }

            // Create FormData
            const formData = new FormData(this);

            // Manually append 'ket' and 'doc' based on status
            if (status.value === 'I') {
                const alasanIzin = document.getElementById('alasanIzin').value;
                formData.append('ket', alasanIzin);
                
                const fileInput = document.getElementById('fileInputIzin');
                if (fileInput.files.length > 0) {
                    formData.append('doc', fileInput.files[0]);
                }
            } else if (status.value === 'S') {
                const jenisSakit = document.getElementById('jenisSakit').value;
                formData.append('ket', jenisSakit);

                const fileInput = document.getElementById('fileInputSakit');
                if (fileInput.files.length > 0) {
                    formData.append('doc', fileInput.files[0]);
                }
            }
            
            // Map fields correctly:
            // - textarea[name="perasaan"] = "Bagaimana perasaan hari ini?" → should be 'catatan' in DB
            // - textarea[name="catatan"] = "Ceritakan perasaan hari ini" → should be 'catatan_ket' in DB
            
            const perasaanValue = document.querySelector('textarea[name="perasaan"]').value;
            const ceritakanValue = document.querySelector('textarea[name="catatan"]').value;
            
            // Remove the existing 'catatan' from FormData (it was auto-added from form)
            formData.delete('catatan');
            formData.delete('perasaan');
            
            // Add with correct mapping
            formData.append('catatan', perasaanValue); // Bagaimana perasaan
            formData.append('catatan_ket', ceritakanValue); // Ceritakan perasaan
            
            console.log('Bagaimana perasaan (catatan):', perasaanValue);
            console.log('Ceritakan perasaan (catatan_ket):', ceritakanValue);
            
            console.log('Sending form data...');
            console.log(formData);
            // Send to Laravel Backend
            const response = await fetch('/siswa/presensi', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            console.log('Response received:', response);
            const data = await response.json();
            console.log('Response data:', data);

            console.log(response.ok);
            
            // Log prediction results for development debugging
            console.log('=== CHECKING FOR DEBUG DATA ===');
            if (data.debug) {
                console.log('✅ Debug data found!');
                console.log('=== PREDICTION RESULTS FROM BACKEND ===');
                console.log('Face Prediction:', data.debug.face_prediction);
                console.log('Text Prediction:', data.debug.text_prediction);
                console.log('========================================');
            } else {
                console.log('❌ No debug data in response');
                console.log('Full response:', data);
            }
            
            if (response.ok) {
                console.log('Success! Redirecting to dashboard...');
                window.location.href = '/siswa/dashboard';
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat mengirim absensi.');
            }

        } catch (error) {
            window.dispatchEvent(new CustomEvent('swal:alert', {detail : {
                icon : 'error',
                title : 'Galat '+error.code,
                text : error.message
            }}))
        } finally {
            submitBtn.innerText = originalBtnText;
            submitBtn.disabled = false;
        }
    });

    // Update date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    document.getElementById('currentDate').textContent = today.toLocaleDateString('id-ID', options);

    // File Upload Logic
    function setupFileUpload(areaId, inputId, previewContainerId, previewImageId, removeBtnId) {
        const area = document.getElementById(areaId);
        const input = document.getElementById(inputId);
        const container = document.getElementById(previewContainerId);
        const img = document.getElementById(previewImageId);
        const removeBtn = document.getElementById(removeBtnId);

        // Trigger input click
        area.addEventListener('click', () => input.click());

        // Handle file selection
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 10MB.');
                    this.value = '';
                    return;
                }

                area.style.display = 'none';
                container.style.display = 'block';

                if (file.type === 'application/pdf') {
                    // Show PDF placeholder
                    img.src = 'https://cdn-icons-png.flaticon.com/512/337/337946.png'; // Generic PDF icon
                    img.style.objectFit = 'contain';
                } else {
                    // Show Image preview
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        img.src = e.target.result;
                        img.style.objectFit = 'cover';
                    }
                    reader.readAsDataURL(file);
                }
            }
        });

        // Handle remove
        removeBtn.addEventListener('click', function() {
            input.value = '';
            area.style.display = 'flex'; // Restore flex display
            container.style.display = 'none';
            img.src = '';
        });
    }

    // Initialize file uploads
    setupFileUpload('uploadAreaIzin', 'fileInputIzin', 'previewContainerIzin', 'previewImageIzin', 'removeImageIzin');
    setupFileUpload('uploadAreaSakit', 'fileInputSakit', 'previewContainerSakit', 'previewImageSakit', 'removeImageSakit');
</script>
@endsection
