@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Kelas</h1>
        </div>
    </div>
@endsection


@section('content')


<div class="row g-4 justify-content-center"  x-data="{selected_id : `{{ old('id') }}`}">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient bg-opacity-50 no-after p-4">
                <div class="row align-items-center gy-2">
                    <div class="col-12 col-md-3">
                        <h6 class="m-0 fw-medium fs-5 text-black-50">Daftar Kelas</h6>
                    </div>
                    <div class="col-12 col-md-9">
                        <form method="GET" class="row g-2">
                            <div class="col-12 col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama kelas..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="jenjang" class="form-select">
                                    <option value="">Semua Jenjang</option>
                                    @foreach($jenjangs as $jenjang)
                                        <option value="{{ $jenjang }}" {{ $jenjangFilter == $jenjang ? 'selected' : '' }}>
                                            {{ $jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="jurusan" class="form-select">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusans as $jurusan)
                                        <option value="{{ $jurusan }}" {{ $jurusanFilter == $jurusan ? 'selected' : '' }}>
                                            {{ $jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-1">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-search"></i>
                                </button>
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
                                <th scope="col" class="py-3 fw-medium">Nama Kelas</th>
                                <th scope="col" class="py-3 fw-medium">Jenjang</th>
                                <th scope="col" class="py-3 fw-medium">Jurusan</th>
                                <th scope="col" class="py-3 fw-medium" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $i => $row)
                                <tr :class="{'table-active' : (selected_id=={{ $row->id }})}">
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $row->nama}}</td>
                                    <td>{{ $row->jenjang }}</td>
                                    <td>{{ $row->jurusan }}</td>
                                    <td class="">
                                        <div class="d-flex gap-1">
                                            <a 
                                            href="#" 
                                            role="button"
                                            class="btn btn-outline-warning btn-sm"
                                            onclick="event.preventDefault();editForm('{{ $row->id }}', `{{ route('admin.kelas.update', ['kelas'=>$row->id]) }}`)"
                                            x-on:click="selected_id={{ $row->id }};"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a 
                                            href="#"
                                            role="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="
                                            event.preventDefault();
                                            setDeleteForm(`{{ route('admin.kelas.destroy', ['kelas'=>$row->id]) }}`);
                                            window.dispatchEvent(new CustomEvent('swal:confirm', {detail : {
                                                title : 'Konfirmasi hapus data',
                                                text : 'Apakah anda yakin ingin menghapus kelas ini?',
                                                icon : 'warning',
                                                method : submitDeleteForm,
                                            }}))"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="bg-light-subtle text-center text-black-50 py-4">
                                        @if($search || $jenjangFilter || $jurusanFilter)
                                            <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                            <div>Tidak ditemukan data kelas yang sesuai</div>
                                            <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <div>Belum ada data kelas</div>
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
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="h5 font-weight-bold text-black-50">Form Kelas</h2>
                <a 
                href="#"
                role="button"
                class="btn"
                onclick="event.preventDefault();resetForm(`{{ route('admin.kelas.store') }}`)">
                    Clear
                </a>
            </div>
            <div class="card-body">
                <form method="post" class="d-none" id="form-delete">
                    @csrf
                    @method('DELETE')
                </form>
                <form 
                @if(old('id')==null)
                action="{{ route('admin.kelas.store') }}" 
                @else
                action="{{ route('admin.kelas.update', ['kelas'=>old('id')]) }}" 
                @endif
                method="POST" id="form-kelas" class="col-12">
                    @csrf
                    <input type="hidden" name="id" id="id_field" x-model="selected_id">
                    <input type="hidden" name="_method" id="_method_field" value="{{ old('id') ? 'PUT' : 'POST' }}">
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="nama_field">
                            <small>Nama</small>
                        </label>
                        <input type="text" id="nama_field" class="form-control" name="nama" value="{{ old('nama') }}">
                        <x-form-error-text :field="'nama'" />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="jenjang_field">
                            <small>Jenjang</small>
                        </label>
                        <select class="form-select" id="jenjang_field" name="jenjang">
                            @for ($i=1;$i<=3;$i++)
                                <option value="{{ $i }}" @selected(old('jenjang')==$i)>{{ $i }}</option>
                            @endfor
                        </select>
                        <x-form-error-text :field="'jenjang'" />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="">
                            <small>Jurusan</small>
                        </label>
                        <select id="jurusan_field" class="form-select" name="jurusan">
                            <option value="IPA" @selected(old('jurusan')=='IPA')>IPA</option>
                            <option value="IPS" @selected(old('jurusan')=='IPS')>IPS</option>
                        </select>
                        <x-form-error-text :field="'jurusan'" />
                    </div>
                    <button type="submit" class="btn btn-primary bg-gradient w-100">Simpan</button>
                </form>
            </div>
            
        </div>
    </div>
</div>
<script>
    const deleteForm=document.getElementById('form-delete');
    const formKelas=document.getElementById('form-kelas');
    const namaField=document.getElementById('nama_field');
    const methodField=document.getElementById('_method_field');
    const jenjangField=document.getElementById('jenjang_field');
    const jurusanField=document.getElementById('jurusan_field');

    const classes=@json($classes->items())

    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;
    function editForm(id, route) {
        const _class=classes.filter(c => c.id==id)
        const form=_class[0]

        formKelas.action=route;
        methodField.value='PUT';
        namaField.value=form.nama;
        jenjangField.value=form.jenjang;
        jurusanField.value=form.jurusan;
    }

    function resetForm(route)
    {
        namaField.value='';
        jenjangField.value='1';
        jurusanField.value='IPA';
        formKelas.action=route;
        methodField.value='POST'
    }
</script>
@endsection