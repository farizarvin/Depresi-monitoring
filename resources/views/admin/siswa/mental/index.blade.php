

@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Dashboard kesehatan mental siswa</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-7">
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
                                <th class="py-4 border-0 col-3">Tingkat Depresi</th>
                                <th class="py-4 border-0 col-2">Label</th>
                                <th scope="col" class="py-4 border-0 col-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-divide">
                            <tr>
                                <td class="">1</td>
                                <td>
                                    <div class="font-weight-bold">Harun Manunggal</div>
                                    <div class="text-secondary">1234567890</div>
                                </td>
                                <td>
                                    XIIB-IPA
                                </td>
                                <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div class="progress-bar bg-primary position-relative" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                        
                                        </div>
                                        
                                    </div>
                                    <div class="w-100 d-flex flex-wrap">
                                        <div class="text-center" style="width: 25%;">
                                            25%
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <span>
                                        Depresi
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary ">
                                        <i class="fas fa-eye"></i>
                                        
                                    </button>

                                </td>

                            </tr>
                        </tbody>
                        {{-- {{ $slot }} --}}
                    </table>

                    
                    
                </div>
            </div>
            <div class="card-footer bg-white border-top d-flex justify-content-between no-after">
                <section class="d-flex">
                  
                    
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

    <div class="col-4">
        <div class="card shadow-none">
            
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-2 border-0">
                                    No
                                </th>
                                <th class="col-4 border-0">
                                    Date
                                </th>
                                <th class="col-3 border-0">
                                    Result
                                </th>
                                <th class="col-2 border-0">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td>
                                    19 September 2025
                                </td>
                                <td>
                                    Marah
                                </td>
                                <td>
                                    <a href="#">More <i class="fas fa-caret-down"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
