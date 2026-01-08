<div class="mb-3">
    <label class="form-label">Pilih Status Kehadiran</label>
    <div class="status-selection">
        <div class="status-option">
            <input type="radio" name="status" id="hadir" value="H" class="status-radio" required>
            <label for="hadir" class="status-label hadir">
                <i class="bi bi-check-circle-fill status-icon hadir"></i>
                <span class="status-text">Hadir</span>
            </label>
        </div>

        <div class="status-option">
            <input type="radio" name="status" id="izin" value="I" class="status-radio">
            <label for="izin" class="status-label izin">
                <i class="bi bi-clock-fill status-icon izin"></i>
                <span class="status-text">Izin</span>
            </label>
        </div>

        <div class="status-option">
            <input type="radio" name="status" id="sakit" value="S" class="status-radio">
            <label for="sakit" class="status-label sakit">
                <i class="bi bi-x-circle-fill status-icon sakit"></i>
                <span class="status-text">Sakit</span>
            </label>
        </div>
    </div>
</div>
