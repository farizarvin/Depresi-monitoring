<div id="formSakit" class="conditional-form">
    <label class="form-label">Sakit Apa?</label>
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
        <img id="previewImageSakit" class="preview-image" alt="Preview">
        <button type="button" class="remove-image" id="removeImageSakit">
            <i class="bi bi-x-circle"></i> Hapus
        </button>
    </div>
</div>
