<div id="formIzin" class="conditional-form">
    <label class="form-label">Alasan Izin <span class="text-danger">*</span></label>
    <input 
        type="text" 
        id="alasanIzin" 
        name="ket" 
        class="form-input mb-3" 
        placeholder="Contoh: Acara keluarga, keperluan pribadi, dll"
    >

    <label class="form-label">Upload Surat Izin (Opsional)</label>
    <div class="upload-area small" id="uploadAreaIzin">
        <i class="bi bi-file-earmark-arrow-up upload-icon"></i>
        <p class="upload-text">Klik untuk Upload Surat Izin</p>
        <p class="upload-subtext">JPG, PNG, PDF maksimal 10MB</p>
        <input type="file" id="fileInputIzin" class="file-input" accept="image/jpeg,image/png,image/jpg,application/pdf">
    </div>
    <div class="preview-container" id="previewContainerIzin" style="display: flex; flex-direction: column; gap: 0.75rem; align-items: center;">
        <img id="previewImageIzin" class="preview-image" alt="Preview" style="width: 100%; max-width: 400px;">
        <button type="button" class="btn btn-danger btn-sm" id="removeImageIzin" style="width: 100%; max-width: 400px;">
            <i class="bi bi-x-circle"></i> Hapus
        </button>
    </div>
    <div id="uploadErrorIzin" class="upload-error" style="display: none; color: #EF4444; font-size: 0.8125rem; margin-top: 0.5rem; padding: 0.5rem; background: #FEE2E2; border-radius: 0.5rem; border-left: 3px solid #EF4444;">
        <i class="bi bi-exclamation-circle me-1"></i>
        <span id="uploadErrorTextIzin"></span>
    </div>
</div>
