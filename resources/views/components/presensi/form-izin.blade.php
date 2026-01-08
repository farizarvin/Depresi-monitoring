<div id="formIzin" class="conditional-form">
    <label class="form-label">Alasan Izin</label>
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
    <div class="preview-container" id="previewContainerIzin">
        <img id="previewImageIzin" class="preview-image" alt="Preview">
        <button type="button" class="remove-image" id="removeImageIzin">
            <i class="bi bi-x-circle"></i> Hapus
        </button>
    </div>
</div>
