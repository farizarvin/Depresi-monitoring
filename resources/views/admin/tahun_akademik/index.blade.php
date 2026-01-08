@extends('layouts.admin')

@section('title', 'Index Tahun Akademik')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Tahun Akademik</h1>
        </div>
    </div>
@endsection


@section('content')

<div class="row g-4 justify-content-center"  x-data="{selected_id : `{{ old('id') }}`}">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient bg-opacity-50 no-after p-4">
                <h6 class="m-0 fw-medium fs-5 text-black-50 mb-3">Daftar Tahun Akademik</h6>
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-12 col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Cari tahun akademik (contoh: 2026)..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-8 col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="current" {{ $statusFilter == 'current' ? 'selected' : '' }}>Current</option>
                            <option value="active" {{ $statusFilter == 'active' ? 'selected' : '' }}>Opened</option>
                            <option value="inactive" {{ $statusFilter == 'inactive' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-4 col-md-1">
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
                                <th scope="col" class="py-3 fw-medium">Periode</th>
                                <th scope="col" class="py-3 fw-medium">Tanggal Mulai</th>
                                <th scope="col" class="py-3 fw-medium">Tanggal Selesai</th>
                                <th scope="col" class="py-3 fw-medium">Status</th>
                                <th scope="col" class="py-3 fw-medium" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($academicYears as $i => $row)
                                <tr 
                                class=""
                                :class="{'table-active' : (selected_id=={{ $row->id }}) }">
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $row->nama_tahun}}</td>
                                    <td>{{ $row->tanggal_mulai }}</td>
                                    <td>{{ $row->tanggal_selesai }}</td>
                                    <td>
                                        @if($row->current)
                                            <span class="badge rounded-pill bg-success bg-opacity-75">current</span>
                                        @elseif($row->status)
                                            <span class="badge rounded-pill bg-primary bg-opacity-75">opened</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger bg-opacity-75">closed</span>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a 
                                            href="#" 
                                            role="button"
                                            class="btn btn-outline-warning btn-sm"
                                            onclick="event.preventDefault();editForm('{{ $row->id }}', `{{ route('admin.tahun-akademik.update', ['tahun_akademik'=>$row->id]) }}`)"
                                            x-on:click="selected_id={{ $row->id }};">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a 
                                            href="#"
                                            role="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="
                                            event.preventDefault();
                                            setDeleteForm(`{{ route('admin.tahun-akademik.destroy', ['tahun_akademik'=>$row->id]) }}`);
                                            window.dispatchEvent(new CustomEvent('swal:confirm', {detail : {
                                                title : 'Konfirmasi hapus data',
                                                text : 'Apakah anda yakin ingin menghapus tahun akademik ini?',
                                                icon : 'warning',
                                                method : submitDeleteForm,
                                            }}))">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="bg-light-subtle text-center text-black-50 py-4">
                                        @if($search || $statusFilter)
                                            <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                            <div>Tidak ditemukan tahun akademik yang sesuai</div>
                                            <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <div>Belum ada tahun akademik</div>
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
                    {{ $academicYears->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="fs-5 fw-medium m-0 text-black-50">Form Tahun Akademik</h2>
                <a 
                href="#"
                role="button"
                class="btn"
                onclick="event.preventDefault();resetForm(`{{ route('admin.tahun-akademik.store') }}`)">
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
                    action="{{ route('admin.tahun-akademik.store') }}" 
                @else  
                    action="{{ route('admin.tahun-akademik.update', ['tahun_akademik'=>old('id')]) }}"
                @endif
                method="POST"
                class="w-100" id="form-tahun-akademik">
                @csrf
                <input type="hidden" name="id" id="id_field" x-model="selected_id">
                <input type="hidden" name="_method" id="_method_field" value="{{ old('id')==null ? 'POST' : 'PUT' }}">
                <div class="mb-3">
                    <label class="form-label fw-medium" for="tahun_mulai_field">
                        <small>Periode Tahun</small>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="tahun_mulai_field" placeholder="Awal" name="tahun_mulai" value="{{ old('tahun_mulai') }}">
                        <input type="text" class="form-control" id="tahun_akhir_field" placeholder="Akhir" name="tahun_akhir" value="{{ old('tahun_akhir') }}">
                        {{-- <div class="input-group-append"> --}}
                            <div class="input-group-text">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="status_field" name="status" value="1" @checked(old('status'))>
                                {{-- <label class="p-0 m-0 pl-2 d-inline font-weight-normal">Open</label> --}}
                            </div>
                        {{-- </div> --}}
                        
                    </div>
                    <x-form-error-text :field="'periode'" />
                    <x-form-error-text :field="'tahun_mulai'" />
                    <x-form-error-text :field="'tahun_akhir'" />
                    <x-form-error-text :field="'status'" />
                </div>
                
                
                <div class="mb-3">
                    <label class="form-label fw-bold" for="tanggal_mulai_field">
                        <small>Tanggal</small>
                    </label>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-medium" for="tanggal_mulai_field" class="font-weight-normal">
                                <small>Mulai</small>
                            </label>
                            <input type="date" class="form-control form-control-sm py-3" id="tanggal_mulai_field" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            <x-form-error-text :field="'tanggal_mulai'" />
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label fw-medium" for="tanggal_selesai_field" class="font-weight-normal">
                                <small>Selesai</small>
                            </label>
                            <input type="date" class="form-control form-control-sm py-3" id="tanggal_selesai_field" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                            <x-form-error-text :field="'tanggal_selesai'" />
                        </div>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="hidden" name="current" value="0">
                    <input type="checkbox"  id="current_field" class="form-check-input" name="current" value="1" @checked(old('current'))>
                    <label for="" class="fw-medium">
                        <small>Set as current</small>
                    </label>
                    <x-form-error-text :field="'current'" />

                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </form>
            </div>
           
        </div>
    </div>
</div>
<script>
    const deleteForm=document.getElementById('form-delete');
    const formTahunAkademik=document.getElementById('form-tahun-akademik');
    const methodField=document.getElementById('_method_field');
    const fields=['tahun_mulai', 'tahun_akhir', 'status', 'current', 'tanggal_mulai', 'tanggal_selesai', 'id'];
    const fieldEls=Object.fromEntries(fields.map(key=>[key, document.getElementById(`${key}_field`)]))
    const academicYears=@json($academicYears->items())

    

    function editForm(id, route) {
        const academicYear=academicYears.filter(c => c.id==id)
        const form=academicYear[0]

        formTahunAkademik.action=route;
        methodField.value='PUT';
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=form[key]
        })
        fieldEls['current'].value="1";
        fieldEls['status'].value="1";
        fieldEls['current'].checked=form['current']
        fieldEls['status'].checked=form['status']
    }
    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;

    function resetForm(route)
    {
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=null
        })
        fieldEls['current'].checked=false
        fieldEls['status'].checked=false
        formTahunAkademik.action=route;
        methodField.value='POST'
    }
    
</script>
@endsection