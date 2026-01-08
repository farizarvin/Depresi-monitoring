<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal-label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="h3" id="edit-modal-label">Form Edit Tahun Akademik</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-between w-100">
                    <form action="" method="POST" class="col-12"  wire:submit="save()">
                        @csrf
                        <div class="form-group">
                            <label for="tahun_mulai_field">Periode Tahun</label>
                            <div class="input-group">
                                
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tahun_mulai_field" placeholder="Awal" wire:model="form.tahun_mulai">
                                <input type="text" class="form-control" id="tahun_akhir_field" placeholder="Akhir" wire:model="form.tahun_akhir">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <input type="checkbox" id="status_field" wire:model="form.status">
                                        <label class="p-0 m-0 pl-2 d-inline font-weight-normal">Open</label>
                                    </div>
                                </div>
                                
                            </div>
                            <x-form-error-text :field="'periode'" />
                            <x-form-error-text :field="'tahun_mulai'" />
                            <x-form-error-text :field="'tahun_akhir'" />
                            <x-form-error-text :field="'status'" />
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="tanggal_mulai_field">Tanggal</label>
                            <div class="d-flex" style="gap: 1rem;">
                                <div class="form-group flex-fill px-0">
                                    <label for="tanggal_mulai_field" class="font-weight-normal">Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai_field" wire:model="form.tanggal_mulai">
                                    <x-form-error-text :field="'tanggal_mulai'" />
                                </div>
                                
                                <div class="form-group flex-fill px-0">
                                    <label for="tanggal_selesai_field" class="font-weight-normal">Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai_field" wire:model="form.tanggal_selesai">
                                    <x-form-error-text :field="'tanggal_selesai'" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" id="current_field" class="form-check-input" wire:model="form.current">
                            <label for="" class="font-weight-normal">Set as current</label>
                            <x-form-error-text :field="'current'" />

                        </div>
                        <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>