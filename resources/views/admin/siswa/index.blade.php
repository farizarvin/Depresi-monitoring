

@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Siswa</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="row g-4" x-data="{selected_id : `{{ old('id') }}`, id_user : `{{ old('id_user') }}`}" x-ref="mainContainer">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient bg-opacity-50 no-after p-4">
                <h6 class="m-0 fw-medium fs-5 text-black-50 mb-3">Daftar Siswa</h6>
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau NISN..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-4 col-md-2">
                        <select name="class" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $classFilter == $class->id ? 'selected' : '' }}>
                                    {{ $class->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-4 col-md-2">
                        <select name="gender" class="form-select">
                            <option value="">Semua Gender</option>
                            <option value="1" {{ $genderFilter === '1' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="0" {{ $genderFilter === '0' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-none">
                        <thead class="text-black-50">
                            <tr>
                                <th scope="col" class="text-center py-3 fw-medium">No</th>
                                <th scope="col" class="py-3 fw-medium">Siswa</th>
                                <th scope="col" class="py-3 fw-medium">Gender</th>
                                <th scope="col" class="py-3 fw-medium">Kelas</th>
                                <th scope="col" class="py-3 fw-medium">Status</th>
                                <th scope="col" class="py-3 fw-medium" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $i => $row)
                                <tr :class="{'table-active' : selected_id==`{{ $row->id }}`}">
                                    <td class="text-center text-dark">
                                        {{ $i + 1 }}
                                    </td>
                                    
                                    <td>
                                        <small>
                                            <div class="fw-medium">{{ $row->nama_lengkap }}</div>
                                            <div class="text-black-50">{{ $row->nisn}}</div>
                                        </small>
                                        
                                    </td>
                                    <td>{{ $row->gender ? "L" : "P" }}</td>
                                    <td>{{ $row->getKelasAktif()?->nama }}</td>
                                    <td>

                                        @if(!$row->status)
                                            <div class="badge badge-sm bg-danger bg-opacity-50 rounded-pill">Non-Aktif</div>
                                        @else
                                            <div class="badge badge-sm bg-success bg-opacity-75 rounded-pill">Aktif</div>
                                        @endif
                                    </td>
                                    <td >
                                        <a 
                                        href="#" 
                                        role="button"
                                        class="btn btn-sm btn-warning"
                                        x-on:click="event.preventDefault();selected_id=`{{ $row->id }}`"
                                        onclick="editForm(`{{ $row->id }}`, `{{ route('admin.siswa.update', ['siswa'=>$row->id]) }}`, `{{ $row->user->id }}`)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a 
                                        href="#" 
                                        role="button"
                                        class="btn btn-sm btn-danger"
                                        x-on:click="event.preventDefault();setDeleteForm(`{{ route('admin.siswa.destroy', ['siswa'=>$row->id]) }}`)"
                                        onclick="window.dispatchEvent(new CustomEvent('swal:confirm', {detail : {
                                            title : 'Konfirmasi hapus data',
                                            text : 'Apakah anda yakin ingin menghapus siswa ini?',
                                            icon : 'warning',
                                            method : submitDeleteForm,
                                        }}))"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="bg-light-subtle text-center text-black-50 py-4">
                                        @if($search || $classFilter || $statusFilter !== null || $genderFilter !== null)
                                            <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                            <div>Tidak ditemukan data siswa yang sesuai</div>
                                            <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <div>Belum ada data siswa</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4 px-4">
                <div class="d-flex justify-content-left">
                    {{ $students->links() }}
                </div>
            </div>
        </div>

        <form 
            method="post" 
            id="form-delete" 
            class="d-none"
        >
            @csrf
            @method('DELETE')
        </form>
    </div>
    
    <div class="col-12 col-lg-4">
        <form 
            @if(old('id')==null)
            action="{{ route('admin.siswa.store') }}"
            @else
            action="{{ route('admin.siswa.update', ['siswa'=>old('id')]) }}"
            @endif
            method="POST" 
            id="form-siswa"
            class="d-flex flex-column gap-4" 
            enctype="multipart/form-data"
        >
        @csrf
        <input type="hidden" name="id" id="id_field" x-model="selected_id">
        <input type="hidden" name="avatar_url" id="avatar_url_field" value="{{ old('avatar_url') }}">
        <input type="hidden" name="id_user" id="id_user_field" value="{{ old('id_user') }}">
        <input type="hidden" name="_method" id="_method_field" value="{{ old('id') ? 'PUT' : 'POST' }}">

        <div class="col-12">
            <div class="card shadow-sm ">
                <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                    <h2 class="fs-5 fw-medium text-black-50 m-0">Form Siswa</h2>
                    <a 
                    href="#"
                    role="button"
                    class="btn"
                    onclick="event.preventDefault();resetForm(`{{ route('admin.siswa.store') }}`)">
                        Clear
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="" class="form-label fw-medium">
                            <small>Nama Lengkap</small>
                        </label>
                        <input type="text" id="nama_lengkap_field" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                        <x-form-error-text :field="'nama_lengkap'" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label fw-medium">
                            <small>NISN</small>
                        </label>
                        <input type="text" id="nisn_field" class="form-control" name="nisn" value="{{ old('nisn') }}">
                        <x-form-error-text :field="'nisn'" />
                    </div>
                    
                    <div class="row">
                        <div class="col mb-3">
                            <label for="id_thak_masuk_field" class="form-label fw-medium">
                                <small>Tahun Masuk</small>
                            </label>
                            <select id="id_thak_masuk_field" class="form-select form-select-sm py-2" name="id_thak_masuk" value="{{ old('id_thak_masuk') }}">
                                @forelse ($academicYears as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_tahun }}</option>
                                @empty
                                @endforelse
                            </select>
                            <x-form-error-text :field="'id_thak_masuk'" />
                        </div>
                        
                        <div class="col mb-3">
                            <label for="id_kelas_field" class="form-label fw-medium">
                                <small>Kelas</small>
                            </label>
                            <select id="id_kelas_field" class="form-select form-select-sm py-2" name="id_kelas" value="{{ old('id_kelas') }}">
                                @forelse ($classes as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama }}</option>
                                @empty
                                @endforelse
                            </select>
                            <x-form-error-text :field="'id_kelas'" />
                        </div>
                    </div>

                    <div class="mb-3 my-0">
                        <label for="" class="form-label fw-bold">Lahir</label>
                        <div class="row">
                            <div class="col">
                                <label for="tempat_lahir_field" class="fw-medium form-label">
                                    <small>Tempat</small>
                                </label>
                                <input type="text" id="tempat_lahir_field" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                            </div>
                            <div class="col">
                                <label for="tanggal_lahir_field" class="fw-medium form-label">
                                    <small>Tanggal</small>
                                </label>
                                <input type="date" id="tanggal_lahir_field" class="form-control form-control-sm py-2" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            </div>
                        </div>
                        <x-form-error-text :field="'tempat_lahir'" />
                        <x-form-error-text :field="'tanggal_lahir'" />
                    </div>
                    <div class="form-group">
                        <div class="row" >
                            <div class="col mb-3">
                                <label for="status_field" class="form-label fw-medium">
                                    <small>Status Siswa</small>
                                </label>
                                <select id="riwayat_status_field" class="form-select form-select-sm py-2" name="status" value="{{ old('status') }}">
                                    <option value="NW">Baru</option>
                                    <option value="MM">Pindahan</option>
                                </select>
                                <x-form-error-text :field="'status'" />
                            </div>
                            <div class="d-flex flex-wrap mb-3 col">
                                <label class="form-label fw-medium">
                                    <small>Gender</small>
                                </label>
                                <div class="input-group d-flex align-items-end" style="gap: 1rem;">
                                    <div class="form-check">
                                        <input type="radio" name="gender" id="gen_l" class="form-check-input" value="1" name="gender" @checked(old('gender')==1)>
                                        <label for="gen_l" class="form-check-label">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="gender" id="gen_p" class="form-check-input" value="0" name="gender" @checked(old('gender')==0)>
                                        <label for="gen_p" class="form-check-label">Perempuan</label>
                                    </div>
                                    <x-form-error-text :field="'gender'" />
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label for="alamat_field" class="form-label fw-medium">
                            <small>Alamat</small>
                        </label>
                        <textarea id="alamat_field" cols="30" rows="4" class="form-control" name="alamat">{{ old('alamat') }}</textarea>
                        <x-form-error-text :field="'alamat'" />
                    </div>
                    <button type="submit" class="btn btn-primary py-2 bg-gradient w-100">Submit</button>
                        
                </div>
                
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm ">
                <div class="card-header py-4  bg-primary bg-gradient bg-opacity-50">
                    <h2 class="fs-5 fw-medium text-black-50 m-0">Info Akun</h2>
                    <div class="btn"></div>
                </div>
                <div class="card-body">
                    <div 
                    class="mb-3" 
                    x-data="{
                        img_url: '',
                        current_user_id: null,
                        img_preview: null,
                        has_new_upload: false,
                        
                        cleanup() {
                            // Revoke old blob URL to prevent memory leaks and caching
                            if(this.img_preview && this.img_preview.startsWith('blob:')) {
                                URL.revokeObjectURL(this.img_preview);
                            }
                            this.img_preview = null;
                        },
                        
                        reset_preview() {
                            this.cleanup();
                            this.has_new_upload = false;
                            if(this.$refs.img_upload) {
                                this.$refs.img_upload.value = null;
                            }
                            // Re-fetch existing avatar if available
                            this.get_img();
                        },
                        
                        async get_img() {
                            // Don't fetch if user just uploaded new file
                            if(this.has_new_upload) {
                                return;
                            }
                            
                            // Cleanup previous preview
                            this.cleanup();
                            
                            // Only fetch if there's an existing avatar and user ID
                            if(this.img_url && this.current_user_id) {
                                try {
                                    // Removed 'id/' prefix to match storage structure
                                    const url = `/files/images/users/${this.current_user_id}/${this.img_url}`;
                                    
                                    const res = await fetch(url, {cache: 'no-store'});
                                    
                                    if(res.ok) {
                                        const blob = await res.blob();
                                        const blobUrl = URL.createObjectURL(blob);
                                        this.img_preview = blobUrl;
                                    }
                                } catch(e) {
                                    // Silent fail or minimal logging if needed
                                }
                            }
                        },
                        
                        handleFileSelect(event) {
                            this.cleanup();
                            this.has_new_upload = true;
                            const file = event.target.files[0];
                            if(file) {
                                this.img_preview = URL.createObjectURL(file);
                            }
                        }
                    }"
                    x-init="$watch('img_url', () => get_img()); $watch('current_user_id', () => get_img())"
                    x-ref="imgContainer"
                >
                    <label for="" class="fw-bold form-label">Foto Profil</label>
                    
                    <!-- Show preview if image exists (either existing or newly uploaded) -->
                    <div class="w-50 mb-3 position-relative" x-show="img_preview" style="display: none;">
                        <img x-bind:src="img_preview" alt="" class="w-100 d-block" style="aspect-ratio: 1/1; object-fit: cover; border: 2px solid #E5E7EB; border-radius: 8px;">
                        <!-- Only show delete button for new uploads -->
                        <button 
                        type="button" 
                        class="btn btn-danger position-absolute p-0 w-25 rounded-pill" 
                        style="top: 0px; right: 0px; aspect-ratio: 1/1; transform: translate(50%, -50%)"
                        x-show="has_new_upload"
                        x-on:click="reset_preview">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <input 
                    type="file" 
                    x-ref="img_upload"  
                    id="avatar_field" 
                    class="form-control-file form-control-file-sm d-block" 
                    accept="image/jpeg,image/jpg,image/png"
                    x-on:change="handleFileSelect($event)"
                    name="avatar">
                    <small class="text-muted d-block mt-1">Format: JPG, PNG. Maksimal: 512KB</small>
                    <x-form-error-text :field="'avatar'" />

                </div>
                    <div class="mb-3">
                        <label for="" class="form-label fw-medium">
                            <small>Email</small>
                        </label>
                        <input type="text" id="email_field" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    const deleteForm=document.getElementById('form-delete');
    const formSiswa=document.getElementById('form-siswa');
    const methodField=document.getElementById('_method_field');
    const fields=['nama_lengkap', 'nisn', 'id_thak_masuk', 'id_kelas', 'tempat_lahir', 'tanggal_lahir', 'riwayat_status', 'email', 'alamat', 'avatar_url', 'id_user'];
    const fieldEls=Object.fromEntries(fields.map(key=>[key, document.getElementById(`${key}_field`)]))
    const genderFields=document.querySelectorAll('input[name="gender"]')

    const students=@json($students->items())

    

    function editForm(id, route, userId) {
        const data=Alpine.$data(document.querySelector('[x-ref="mainContainer"]'))
        const student=students.filter(c => c.id==id)
        const form=student[0]
        data.id_user=userId;
        

        formSiswa.action=route;
        methodField.value='PUT';
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=form[key]
        })
        genderFields.forEach(gf=>{
            gf.checked=gf.value==form['gender']
        })
        
        // Set the avatar URL with userId (will trigger get_img via $watch)
        updateImgURL(form['avatar_url'], userId)
        
    }
    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;

    function resetForm(route)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="mainContainer"]'))
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=null
        })
        genderFields.forEach(gf=>{
            gf.checked=false
        })
        formSiswa.action=route;
        methodField.value='POST';
        data.selected_id=null;
    }
    function updateImgURL(url, userId)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="imgContainer"]'))
        
        // Sequence to avoid race conditions and ensure proper cleanup:
        // 1. Clear img_url triggers get_img -> cleanup() -> clears preview
        data.img_url = null; 
        
        // 2. Set new user ID (no fetch yet because img_url is null)
        data.current_user_id = userId;
        
        // 3. Set new img_url triggers get_img -> fetches new image
        // Use timeout to ensure reactivity processes the changes
        setTimeout(() => {
            data.img_url = url || '';
        }, 50);
    }
    
</script>
@endsection
