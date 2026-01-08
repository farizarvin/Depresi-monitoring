<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal-label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="h2" id="edit-modal-label">Form Edit Siswa</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form 
                action="" 
                method="POST" 
                class="d-flex justify-between w-100"  
                wire:submit="save()">
                    <div class="col-8">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Lengkap</label>
                            <input type="text" id="" class="form-control" wire:model="form.nama_lengkap">
                            <x-form-error-text :field="'nama_lengkap'" />
                        </div>
                        <div class="form-group">
                            <label for="">NISN</label>
                            <input type="text" id="" class="form-control" wire:model="form.nisn">
                            <x-form-error-text :field="'nisn'" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" id="" class="form-control" wire:model="form.email">
                            <x-form-error-text :field="'email'" />
                        </div>
                        <div class="form-group">
                            <label for="id_thak_masuk_field">Tahun Masuk</label>
                            <select id="id_thak_masuk_field" class="form-control" wire:model="form.id_thak_masuk">
                                @if($thak)
                                    @foreach ($thak as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama_tahun }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-form-error-text :field="'id_thak_masuk'" />
                        </div>
                        
                        <div class="form-group">
                            <label for="id_kelas_field">Kelas</label>
                            <select id="id_kelas_field" class="form-control" wire:model="form.id_kelas">
                                @if($kelas)
                                    @foreach ($kelas as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-form-error-text :field="'id_kelas'" />

                        </div>
                        <div class="form-group my-0">
                            <label for="">Lahir</label>
                            <div class="d-flex flex-wrap justify-content-between " style="gap: 1rem;font-size: 16px;">
                                <div class="form-group flex-fill px-0">
                                    <label for="tempat_lahir_field" class="font-weight-normal">Tempat</label>
                                    <input type="text" id="tempat_lahir_field" class="form-control" wire:model="form.tempat_lahir">
                                </div>
                                <div class="form-group flex-fill px-0">
                                    <label for="tanggal_lahir_field" class="font-weight-normal">Tanggal</label>
                                    <input type="date" id="tanggal_lahir_field" class="form-control" wire:model="form.tanggal_lahir">
                                </div>
                            </div>
                            <x-form-error-text :field="'tempat_lahir'" />
                            <x-form-error-text :field="'tanggal_lahir'" />
                        </div>
                        <div class="form-group">
                            <div class="d-flex flex-wrap justify-content-between" style="gap: 3rem;">
                                <div class="form-group flex-fill px-0">
                                    <label for="status_field">Status Siswa</label>
                                    <select id="status_field" class="form-control" wire:model="form.status">
                                        <option value="NW">Baru</option>
                                        <option value="MM">Pindahan</option>
                                    </select>
                                    <x-form-error-text :field="'status'" />
                                </div>
                                <div class="form-group d-flex flex-wrap flex-fill px-0">
                                    <label>Gender</label>
                                    <div class="input-group d-flex align-items-end" style="gap: 1rem;">
                                        <div class="form-check">
                                            <input type="radio" name="gender" id="gen_l" class="form-check-input" value="1" wire:model="form.gender">
                                            <label for="gen_l" class="form-check-label">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="gender" id="gen_p" class="form-check-input" value="0" wire:model="form.gender">
                                            <label for="gen_p" class="form-check-label">Perempuan</label>
                                        </div>
                                        <x-form-error-text :field="'gender'" />
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="alamat_field">Alamat</label>
                            <textarea id="alamat_field" cols="30" rows="4" class="form-control" wire:model="form.alamat"></textarea>
                            <x-form-error-text :field="'alamat'" />
                        </div>
                        <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                    </div>
                    
                    <div class="col-4 px-4">
                        <div 
                            class="form-group" 
                            x-data="{
                                img_url : @entangle('form.avatar_url'), 
                                img_preview : null,
                                img_initial : null,
                                reset_preview() 
                                {
                                    this.img_preview=null;
                                    $refs.img_upload.value=null;
                                },
                                async get_img() 
                                {
                                    this.reset_preview();
                                    const id=$wire.id_user
                                    const url=this.img_url ? `id/${id}/${this.img_url==''?'p':this.img_url}` : 'default'
                                    const res=await fetch(`/files/images/users/${url}`)
                                    const blob=await res.blob();
                                    
                                    this.img_initial=URL.createObjectURL(blob);
                                }
                            }"
                            x-init="get_img;$watch('img_url', _=>get_img()) "
                            
                        >
                            <label for="">Foto Profil</label>
                            <div class="w-50 mb-3 position-relative">
                                <img x-bind:src="img_preview || img_initial" alt="" class="w-100 d-block" style="aspect-ratio: 1/1;object-fit: cover;">
                                <button 
                                type="button" 
                                class="btn btn-danger position-absolute p-0 w-25 rounded-pill" 
                                style="top: 0px; right: 0px;aspect-ratio: 1/1;transform: translate(50%, -50%)"
                                x-show="img_preview!==null"
                                x-on:click="reset_preview">
                                    X
                                </button>
                            </div>
                            <input 
                            type="file" 
                            x-ref="img_upload"  
                            id="avatar_field" 
                            class="form-control-file form-control-file-sm d-block" 
                            x-on:change="img_preview=URL.createObjectURL(event.target.files[0])"
                            wire:model="form.avatar">
                            <x-form-error-text :field="'avatar'" />

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>