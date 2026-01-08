<div class="modal fade" id="create-modal" tabindex="-1" aria-labelledby="create-modal-label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="h2" id="create-modal-label">Form Tambah Kelas</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-between w-100">
                    <form action="" method="POST" class="col-12"  wire:submit="save()">
                        @csrf
                        <div class="form-group">
                            <label for="nama_field">Nama</label>
                            <input type="text" id="nama_field" class="form-control" wire:model="form.nama">
                            <x-form-error-text :field="'nama'" />
                        </div>
                        
                        <div class="form-group">
                            <label for="jenjang_field">Jenjang</label>
                            <select class="form-control" id="jenjang_field" wire:model="form.jenjang">
                                <option value="1" selected="true">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <x-form-error-text :field="'jenjang'" />
                        </div>
                        
                        <div class="form-group">
                            <label for="">Jurusan</label>
                            <select id="jurusan_field" class="form-control" wire:model="form.jurusan">
                                <option value="rpl" selected="true">Rekayasa Perangkat Lunak</option>
                                <option value="dkv">Desain Komunikasi Visual</option>
                                <option value="tkj">Teknik Komputer Jaringan</option>
                                <option value="tkr">Teknik Kendaraan Ringan</option>
                            </select>
                            <x-form-error-text :field="'jurusan'" />
                        </div>
                        <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>