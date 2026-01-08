@extends('layouts.admin')

@section('title', 'Index Guru')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Manajemen Guru</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="row g-4" x-data="{selected_id : `{{ old('id') }}`, id_user : `{{ old('id_user') }}`}" x-ref="mainContainer">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient bg-opacity-50 no-after p-4">
                <div class="row align-items-center gy-2">
                    <div class="col-12 col-md-4">
                        <h6 class="m-0 fw-medium fs-5 text-black-50">Daftar Guru</h6>
                    </div>
                    <div class="col-12 col-md-8">
                        <form method="GET" class="row g-2 justify-content-end">
                            <div class="col-12 col-md-7">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control border-end-0" placeholder="Cari nama atau NIP..." value="{{ $search ?? '' }}">
                                    <button type="submit" class="btn btn-warning border-start-0">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <select name="gender" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Gender</option>
                                    <option value="1" {{ $genderFilter === '1' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="0" {{ $genderFilter === '0' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-none">
                        <thead class="text-black-50">
                            <tr>
                                <th scope="col" class="text-center py-3 fw-medium">No</th>
                                <th scope="col" class="py-3 fw-medium">Guru</th>
                                <th scope="col" class="py-3 fw-medium">NIP</th>
                                <th scope="col" class="py-3 fw-medium">Gender</th>
                                <th scope="col" class="py-3 fw-medium" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $i => $row)
                                <tr :class="{'table-active' : selected_id==`{{ $row->id }}`}">
                                    <td class="text-center text-dark">
                                        {{ $i + 1 }}
                                    </td>
                                    
                                    <td>
                                        <small>
                                            <div class="fw-medium">{{ $row->nama_lengkap }}</div>
                                            <div class="text-black-50">{{ $row->email ?? '-' }}</div>
                                        </small>
                                    </td>
                                    <td>{{ $row->nip }}</td>
                                    <td>{{ $row->gender ? "L" : "P" }}</td>
                                    <td>
                                        <a 
                                        href="#" 
                                        role="button"
                                        class="btn btn-sm btn-warning"
                                        x-on:click="event.preventDefault();selected_id=`{{ $row->id }}`"
                                        onclick="editForm(`{{ $row->id }}`, `{{ route('admin.guru.update', ['guru'=>$row->id]) }}`, `{{ $row->id_user }}`)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a 
                                        href="#" 
                                        role="button"
                                        class="btn btn-sm btn-danger"
                                        x-on:click="event.preventDefault();setDeleteForm(`{{ route('admin.guru.destroy', ['guru'=>$row->id]) }}`)"
                                        onclick="window.dispatchEvent(new CustomEvent('swal:confirm', {detail : {
                                            title : 'Konfirmasi hapus data',
                                            text : 'Apakah anda yakin ingin menghapus guru ini?',
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
                                    <td colspan="5" class="bg-light-subtle text-center text-black-50 py-4">
                                        @if($search || $genderFilter !== null)
                                            <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                            <div>Tidak ditemukan data guru yang sesuai</div>
                                            <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <div>Belum ada data guru</div>
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
                    {{ $teachers->links() }}
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
            action="{{ route('admin.guru.store') }}"
            @else
            action="{{ route('admin.guru.update', ['guru'=>old('id')]) }}"
            @endif
            method="POST" 
            id="form-guru"
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
                <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-success bg-gradient bg-opacity-50">
                    <h2 class="fs-5 fw-medium text-black-50 m-0">Form Guru</h2>
                    <a 
                    href="#"
                    role="button"
                    class="btn"
                    onclick="event.preventDefault();resetForm(`{{ route('admin.guru.store') }}`)">
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
                            <small>NIP</small>
                        </label>
                        <input type="text" id="nip_field" class="form-control" name="nip" value="{{ old('nip') }}">
                        <x-form-error-text :field="'nip'" />
                    </div>

                    <div class="mb-3 my-0">
                        <label for="" class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir_field" class="form-control form-control-sm py-2" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                        <x-form-error-text :field="'tgl_lahir'" />
                    </div>
                    
                    <div class="form-group">
                        <div class="row" >
                            <div class="d-flex flex-wrap mb-3 col">
                                <label class="form-label fw-medium">
                                    <small>Gender</small>
                                </label>
                                <div class="input-group d-flex align-items-end" style="gap: 1rem;">
                                    <div class="form-check">
                                        <input type="radio" name="gender" id="gen_l" class="form-check-input" value="1" @checked(old('gender')==1)>
                                        <label for="gen_l" class="form-check-label">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="gender" id="gen_p" class="form-check-input" value="0" @checked(old('gender')==0)>
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
                            id_user : `{{ old('id_user') }}`,
                            img_url : `{{ old('avatar_url') }}`, 
                            img_preview : null,
                            img_initial : null,
                            is_img_load : false,
                            reset_preview() 
                            {
                                this.img_preview=null;
                                $refs.img_upload.value=null;
                            },
                            async get_img() 
                            {
                                this.reset_preview();
                                
                                try {
                                    const res1=await fetch(`/files/images/users/default`)
                                    if(res1.ok){
                                        const blob1=await res1.blob();
                                        this.img_initial=URL.createObjectURL(blob1);
                                    }
                                } catch(e) {
                                    console.error('Failed to load default image', e);
                                }
                                
                                if(this.img_url && this.id_user)
                                {
                                    try {
                                        const url=`id/${this.id_user}/${this.img_url==''?'p':this.img_url}` 
                                        const res2=await fetch(`/files/images/users/${url}`)
                                        if(res2.ok){
                                            const blob2=await res2.blob();
                                            this.img_preview=URL.createObjectURL(blob2);
                                        }
                                    } catch(e) {
                                        console.error('Failed to load user image', e);
                                    }
                                }


                            }
                        }"
                        x-init="get_img();$watch('img_url', _=>get_img()) "
                        x-ref="imgContainer"
                    >
                        <label for="" class="fw-bold form-label">Foto Profil</label>
                        <div class="w-50 mb-3 position-relative">
                            <img x-bind:src="img_preview || img_initial" alt="Preview Image" class="w-100 d-block rounded border bg-light" style="aspect-ratio: 1/1;object-fit: cover;">
                            <button 
                            type="button" 
                            class="btn btn-danger position-absolute p-0 w-25 rounded-pill shadow-sm" 
                            style="top: 0px; right: 0px;aspect-ratio: 1/1;transform: translate(50%, -50%)"
                            x-show="img_preview!=null"
                            x-on:click="reset_preview">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input 
                        type="file" 
                        x-ref="img_upload"  
                        id="avatar_field" 
                        class="form-control form-control-sm d-block" 
                        x-on:change="img_preview=URL.createObjectURL(event.target.files[0])"
                        name="avatar">
                        <x-form-error-text :field="'avatar'" />

                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label fw-medium">
                            <small>Email</small>
                        </label>
                        <input type="text" id="email_field" class="form-control" name="email" value="{{ old('email') }}">
                        <x-form-error-text :field="'email'" />
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    const deleteForm=document.getElementById('form-delete');
    const formGuru=document.getElementById('form-guru');
    const methodField=document.getElementById('_method_field');
    const fields=['nama_lengkap', 'nip', 'tgl_lahir', 'email', 'alamat', 'avatar_url', 'id_user'];
    const fieldEls=Object.fromEntries(fields.map(key=>[key, document.getElementById(`${key}_field`)]))
    const genderFields=document.querySelectorAll('input[name="gender"]')

    const teachers=@json($teachers->items())

    

    function editForm(id, route, userId) {
        const data=Alpine.$data(document.querySelector('[x-ref="mainContainer"]'))
        const teacher=teachers.filter(c => c.id==id)
        const form=teacher[0]
        data.id_user=userId;
        

        formGuru.action=route;
        methodField.value='PUT';
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=form[key]
        })
        genderFields.forEach(gf=>{
            gf.checked=gf.value==form['gender']
        })
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
        formGuru.action=route;
        methodField.value='POST';
        data.selected_id=null;
        
        // Reset Image
        updateImgURL('', null);
    }
    function updateImgURL(url, userId)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="imgContainer"]'))
        data.id_user=userId;
        data.img_url=url;
        
    }
    

</script>
@endsection
