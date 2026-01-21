<div id="formSakit" class="conditional-form">
    <label class="form-label">Sakit Apa? <span class="text-danger">*</span></label>
    <input 
        type="text" 
        id="jenisSakit" 
        name="ket" 
        class="form-input mb-3" 
        placeholder="Contoh: Demam, flu, sakit kepala, dll"
    >

    <label class="form-label">Upload Surat Keterangan Sakit</label>
    <div class="upload-area small" id="uploadAreaSakit">
        <i class="bi bi-file-earmark-medical upload-icon"></i>
        <p class="upload-text">Klik untuk Upload Surat Sakit</p>
        <p class="upload-subtext">JPG, PNG, PDF maksimal 10MB</p>
        <input type="file" id="fileInputSakit" class="file-input" accept="image/jpeg,image/png,image/jpg,application/pdf">
    </div>
    <div class="preview-container" id="previewContainerSakit">
        <img id="previewImageSakit" class="preview-image" alt="Preview" style="width: 100%; max-width: 400px;">
        <button type="button" class="btn btn-danger btn-sm" id="removeImageSakit" style="width: 100%; max-width: 400px;">
            <i class="bi bi-x-circle"></i> Hapus
        </button>
    </div>
    <div id="uploadErrorSakit" class="upload-error" style="display: none; color: #EF4444; font-size: 0.8125rem; margin-top: 0.5rem; padding: 0.5rem; background: #FEE2E2; border-radius: 0.5rem; border-left: 3px solid #EF4444;">
        <i class="bi bi-exclamation-circle me-1"></i>
        <span id="uploadErrorTextSakit"></span>
    </div>
</div>
