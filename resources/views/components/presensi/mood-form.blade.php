<div class="mb-3">
    <label for="perasaan" class="form-label">Bagaimana perasaan hari ini? <span class="text-danger">*</span></label>
    <textarea 
        id="perasaan" 
        name="perasaan" 
        class="form-textarea" 
        placeholder="Contoh: Senang, sedih, semangat, lelah, dll..."
        rows="3"
    ></textarea>
</div>

<div class="mb-4">
    <label for="catatan" class="form-label">Ceritakan perasaan hari ini <span class="text-danger">*</span></label>
    <textarea 
        id="catatan" 
        name="catatan" 
        class="form-textarea" 
        placeholder="Tulis cerita atau pengalaman hari ini..."
        rows="3"
    ></textarea>
    {{-- Hidden emoji input to satisfy backend validation (Default: 3/Neutral) --}}
    <input type="hidden" name="emoji" value="3">
</div>
