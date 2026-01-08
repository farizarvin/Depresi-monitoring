@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Riwayat Kehadiran</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card shadow-sm mb-4">
            <div class="card-header no-after py-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="input-group">
                        <input type="text" name="" id="" class="form-control" placeholder="search items">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap: .5rem;">
                    <div class="form-group m-0">
                        <select name="" id="" class="form-control bg-light">
                            <option value="">All Kelas</option>
                        </select>
                    </div>
                    <div class="form-group m-0">
                        <select name="" id="" class="form-control bg-light">
                            <option value="">Tahun Ini</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 pb-3">
                <div class="table-responsive">
                    <table class="table border-none">
                        <thead class="bg-white">
                            <tr>
                                <th class="py-4 border-0 col-1">No</th>
                                <th class="py-4 border-0 col-3">Siswa</th>
                                <th class="py-4 border-0 col-2">Kelas</th>
                                <th class="py-4 border-0 col-4">Kehadiran</th>
                                <th scope="col" class="py-4 border-0 col-2">Aksi</th>
                            </tr>
                        </thead>
                        
                        <livewire:siswa.kehadiran.table-data />
                        {{-- {{ $slot }} --}}
                    </table>

                    {{-- <div class="d-flex justify-content-left mt-4">
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a href="#" class="page-link">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>

                                    
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">
                                        1
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="form-group ml-2">
                            <select name="" id="" class="form-control">
                                <option value="10">10</option>
                                <option value="10">25</option>
                                <option value="10">50</option>
                                <option value="10">100</option>
                                <option value="10">250</option>
                            </select>
                        </div>
                    </div> --}}
                    
                </div>
            </div>
            <div class="card-footer bg-white border-top d-flex justify-content-between no-after">
                <section class="d-flex">
                    <div>
                        <h3 class="h6 font-weight-bold">Ket</h3>
                        <ul class="list-unstyled font-italic">
                            <li>
                                <span class="text-success mr-1"><i class="fas fa-circle"></i></span>
                                Hadir
                            </li>
                            <li>
                                <span class="text-danger mr-1"><i class="fas fa-circle"></i></span>
                                Alpha
                            </li>
                            <li>
                                <span class="text-info mr-1"><i class="fas fa-circle"></i></span>
                                Ijin/Sakit
                            </li>
                            
                        </ul>
                    </div>
                    <div>
                        <h3 class="h6 font-weight-bold">Total Pertemuan : <span class="font-italic">14</span></h3>
                    </div>
                </section>
                <div class="d-flex justify-content-left">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>

                                
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    1
                                </a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="form-group ml-2">
                        <select name="" id="" class="form-control bg-light">
                            <option value="10">10</option>
                            <option value="10">25</option>
                            <option value="10">50</option>
                            <option value="10">100</option>
                            <option value="10">250</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <livewire:siswa.kehadiran.detail-view />
</div>
@endsection
