@extends('layouts.siswa')

@section('title', 'Absensi - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Absensi Siswa';
    $pageSubtitle = 'Catat kehadiran dan kelola riwayat absensi Anda';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/presensi.css') }}">
@endsection

@section('content')
    {{-- Notifikasi Presensi Ditutup --}}
    <div id="presensiClosedNotification" class="form-card" style="display: none;">
        <div class="text-center py-5">
            <i class="bi bi-clock-history" style="font-size: 4rem; color: #F59E0B;"></i>
            <h3 class="mt-4 mb-3" style="color: #1F2937; font-weight: 600;">Presensi Tidak Tersedia</h3>
            <p id="closedMessage" class="text-muted mb-4" style="font-size: 1rem;">
                Saat ini berada di luar jam pembelajaran. Silakan kembali pada jam presensi yang telah ditentukan.
            </p>
            <div class="alert alert-warning d-inline-block" style="border-radius: 10px;">
                <i class="bi bi-info-circle me-2"></i>
                <span id="scheduleInfo">Presensi hanya tersedia pada jam pembelajaran aktif</span>
            </div>
            <div class="mt-4">
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary" style="border-radius: 10px; padding: 0.75rem 2rem;">
                    <i class="bi bi-house me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    <div id="loadingState" class="form-card">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Mengecek jadwal presensi...</p>
        </div>
    </div>

    {{-- Form Absensi (hidden by default until schedule check passes) --}}
    <div id="absensiFormContainer" class="form-card" style="display: none;">
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
                <label class="form-label">Ambil Foto Selfie <span class="text-danger">*</span></label>
                <div class="webcam-container" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                    <div id="cameraArea" class="mb-3" style="width: 100%; display: flex; justify-content: center;">
                        <video id="webcam" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 10px; display: none; transform: scaleX(-1);"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div id="placeholder" class="p-5 bg-light rounded border" style="cursor: pointer; width: 100%; max-width: 400px; text-align: center;">
                            <i class="bi bi-camera-fill fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Klik untuk aktifkan kamera</p>
                        </div>
                    </div>
                    
                    <div id="previewArea" style="display: none; flex-direction: column; align-items: center; gap: 0.75rem;" class="mb-3">
                        <img id="photoPreview" src="" class="img-fluid rounded" style="width: 100%; max-width: 400px; transform: scaleX(-1);">
                        <button type="button" id="retakeBtn" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.8125rem; font-weight: 500; white-space: nowrap;">
                            <i class="bi bi-arrow-counterclockwise"></i> Foto Ulang
                        </button>
                    </div>

                    <button type="button" id="captureBtn" class="btn btn-primary" style="display: none; width: 100%; max-width: 400px;">
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

            {{-- Error Message Container --}}
            <div id="errorContainer" class="alert alert-danger" style="display: none;" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span id="errorMessage"></span>
            </div>

            <button type="submit" class="btn-submit">Kirim Absensi</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Check Schedule on Page Load
    async function checkPresensiSchedule() {
        const loadingState = document.getElementById('loadingState');
        const formContainer = document.getElementById('absensiFormContainer');
        const closedNotification = document.getElementById('presensiClosedNotification');
        const closedMessage = document.getElementById('closedMessage');
        const scheduleInfo = document.getElementById('scheduleInfo');
        
        try {
            const response = await fetch('/api/siswa/presensi', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                }
            });
            
            const data = await response.json();
            
            // Hide loading state
            loadingState.style.display = 'none';
            
            if (response.ok && data.data?.is_open) {
                // Schedule is open, show form
                formContainer.style.display = 'block';
            } else {
                // Schedule is closed, show notification
                closedNotification.style.display = 'block';
                
                // Customize message based on error
                if (data.err) {
                    if (data.err.includes('Hari libur') || data.err.includes('libur')) {
                        closedMessage.textContent = 'Hari ini adalah hari libur. Presensi tidak tersedia.';
                        scheduleInfo.textContent = 'Presensi akan tersedia kembali pada hari sekolah aktif';
                    } else if (data.err.includes('ditutup') || data.err.includes('Presensi sudah ditutup')) {
                        closedMessage.textContent = 'Saat ini berada di luar jam pembelajaran. Silakan kembali pada jam presensi yang telah ditentukan.';
                        scheduleInfo.textContent = 'Presensi hanya tersedia pada jam pembelajaran aktif';
                    } else {
                        closedMessage.textContent = data.err;
                    }
                }
            }
        } catch (error) {
            // On error, show the form anyway (fail-safe)
            loadingState.style.display = 'none';
            formContainer.style.display = 'block';
        }
    }
    
    // Run schedule check on page load
    document.addEventListener('DOMContentLoaded', checkPresensiSchedule);

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

    // API Config (from Laravel environment)
    const API_FACE_URL = "{{ config('prediction.face_url') }}";
    const API_TEXT_URL = "{{ config('prediction.text_url') }}";

    // Prediction Functions
    async function predictFace(file) {

        const formData = new FormData();
        formData.append('file', file);
        
        try {
            const response = await fetch(API_FACE_URL, {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            

            
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

        
        try {
            const response = await fetch(API_TEXT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ text: text })
            });
            const res = await response.json();
            

            
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
            previewArea.style.display = 'flex';
            video.style.display = 'none';
            captureBtn.style.display = 'none';
            
            // Stop stream
            stream.getTracks().forEach(track => track.stop());

            // Trigger Face Prediction immediately

            await predictFace(file);

        }, 'image/jpeg');
    });

    // Error Display Function
    function showError(message) {
        const errorContainer = document.getElementById('errorContainer');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorContainer.style.display = 'block';
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideError() {
        const errorContainer = document.getElementById('errorContainer');
        errorContainer.style.display = 'none';
    }

    // Form Submit with Text Prediction
    document.getElementById('absensiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Hide previous errors
        hideError();
        
        const status = document.querySelector('input[name="status"]:checked');
        
        if (!status) {
            showError('Harap pilih status kehadiran!');
            return;
        }

        // Validasi Foto Selfie
        if (!swafotoInput.files || swafotoInput.files.length === 0) {
            showError('Harap ambil foto selfie terlebih dahulu!');
            return;
        }

        if (status.value === 'I') {
            const alasanIzin = document.getElementById('alasanIzin').value.trim();
            if (!alasanIzin) {
                showError('Harap isi alasan izin!');
                return;
            }
        }

        if (status.value === 'S') {
            const jenisSakit = document.getElementById('jenisSakit').value.trim();
            if (!jenisSakit) {
                showError('Harap isi jenis sakit!');
                return;
            }
        }

        // Validasi Bagaimana Perasaan
        const perasaan = document.querySelector('textarea[name="perasaan"]').value.trim();
        if (!perasaan) {
            showError('Harap isi bagaimana perasaan hari ini!');
            return;
        }

        // Validasi Ceritakan Perasaan
        const catatan = document.querySelector('textarea[name="catatan"]').value.trim();
        if (!catatan) {
            showError('Harap ceritakan perasaan hari ini!');
            return;
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
            

            // Send to Laravel Backend
            const response = await fetch('/siswa/presensi', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });


            
            // Log prediction results for development debugging
            // Log prediction results for development debugging
            // if (data.debug) {
            //      ...
            // }
            
            if (response.ok) {

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

    // File Upload Logic - Improved Cancel Handling & Inline Errors
    function setupFileUpload(areaId, inputId, previewContainerId, previewImageId, removeBtnId, errorContainerId, errorTextId) {
        const area = document.getElementById(areaId);
        const input = document.getElementById(inputId);
        const container = document.getElementById(previewContainerId);
        const img = document.getElementById(previewImageId);
        const removeBtn = document.getElementById(removeBtnId);
        const errorContainer = document.getElementById(errorContainerId);
        const errorText = document.getElementById(errorTextId);

        let isDialogOpen = false;
        let lastValue = '';

        // Function to show error
        function showError(message) {
            errorText.textContent = message;
            errorContainer.style.display = 'block';
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorContainer.style.display = 'none';
            }, 5000);
        }

        // Function to hide error
        function hideError() {
            errorContainer.style.display = 'none';
        }

        // Trigger input click
        area.addEventListener('click', () => {
            isDialogOpen = true;
            lastValue = input.value;
            hideError(); // Hide any previous errors
            input.click();
        });

        // Handle file selection
        input.addEventListener('change', function() {
            isDialogOpen = false;
            hideError(); // Hide errors on new selection
            
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showError('Ukuran file terlalu besar! Maksimal 10MB.');
                    this.value = '';
                    // Ensure upload area is visible and properly styled
                    area.style.cssText = 'border: 2px dashed #D1D5DB; border-radius: 0.75rem; padding: 2rem 1.25rem; text-align: center; cursor: pointer; background: #F9FAFB; display: flex; flex-direction: column; align-items: center; justify-content: center;';
                    container.style.display = 'none';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                if (!validTypes.includes(file.type)) {
                    showError('Format file tidak valid! Hanya JPG, PNG, atau PDF.');
                    this.value = '';
                    return;
                }

                hideError();
                area.style.display = 'none';
                container.classList.add('show'); // Use class instead of inline style

                if (file.type === 'application/pdf') {
                    img.src = 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
                    img.style.objectFit = 'contain';
                } else {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        img.src = e.target.result;
                        img.style.objectFit = 'cover';
                    }
                    reader.onerror = () => {
                        showError('Gagal membaca file. Silakan coba lagi.');
                        this.value = '';
                        area.style.cssText = 'border: 2px dashed #D1D5DB; border-radius: 0.75rem; padding: 2rem 1.25rem; text-align: center; cursor: pointer; background: #F9FAFB; display: flex; flex-direction: column; align-items: center; justify-content: center;';
                        container.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // Detect cancel with blur + focus events
        input.addEventListener('blur', function() {
            setTimeout(() => {
                if (isDialogOpen && input.value === lastValue) {
                    isDialogOpen = false;
                    area.style.cssText = 'border: 2px dashed #D1D5DB; border-radius: 0.75rem; padding: 2rem 1.25rem; text-align: center; cursor: pointer; background: #F9FAFB; display: flex; flex-direction: column; align-items: center; justify-content: center;';
                    container.classList.remove('show'); // Use class instead of inline style
                }
            }, 100);
        });

        // Handle remove
        removeBtn.addEventListener('click', function() {
            input.value = '';
            lastValue = '';
            hideError();
            
            // Hide preview container using class
            container.classList.remove('show');
            
            // Show upload area with full styling
            area.style.cssText = 'border: 2px dashed #D1D5DB; border-radius: 0.75rem; padding: 2rem 1.25rem; text-align: center; cursor: pointer; background: #F9FAFB; display: flex; flex-direction: column; align-items: center; justify-content: center;';
            
            // Clear image source
            img.src = '';
            
            isDialogOpen = false;
        });
    }

    // Initialize file uploads with error containers
    setupFileUpload('uploadAreaIzin', 'fileInputIzin', 'previewContainerIzin', 'previewImageIzin', 'removeImageIzin', 'uploadErrorIzin', 'uploadErrorTextIzin');
    setupFileUpload('uploadAreaSakit', 'fileInputSakit', 'previewContainerSakit', 'previewImageSakit', 'removeImageSakit', 'uploadErrorSakit', 'uploadErrorTextSakit');
</script>
@endsection
