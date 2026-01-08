

@extends('layouts.admin')

@section('title', 'Daftar Hari Libur')

@php
    $pageTitle = 'Hari Libur';
    $pageSubtitle = 'Manajemen Hari Libur & Kalender';
@endphp


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Calendar Card - Full width on mobile, 5 cols on large screens -->
        <div class="col-12 col-lg-5 mb-4">
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <div class="card-header py-0 no-after d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="py-4">
                            <h1 class="h3 m-0 p-0 text-dark d-block">
                                <strong>Februari</strong> - <span class="font-weight-normal">2024</span>
                            </h1>
                        </div>
                        <button class="btn ">
                            &lt;
                        </button>
                        <button class="btn">
                            >
                        </button>
                        <div class="form-group m-0">
                            <select 
                            id="" 
                            name="" 
                            class="form-control bg-secondary-subtle">
                                <option value="">Januari</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body" style="overflow-x: hidden;">
                        <ul class="list-unstyled d-flex bg-light">
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Su</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Mo</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Tu</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">We</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Th</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Fr</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Sa</li>
                        </ul>
                        <ul class="list-unstyled">
                            @foreach ($days as $cal)
                                <li>
                                    <ul class="list-unstyled d-flex">
                                        @for ($i=0;$i<$cal[0]['day'];$i++)
                                            <li class="col p-0"></li>
                                        @endfor

                                        @foreach ($cal as $c)
                                            <li class="text-center col p-0" style="aspect-ratio: 1/1;">
                                                <button class="p-0 m-0 btn bg-light rounded-pill font-weight-medium text-dark" style="width: 100%; max-width: 50px; aspect-ratio: 1/1; font-size: clamp(12px, 3vw, 16px);">
                                                    {{ $c['date'] }}
                                                </button>
                                            </li>
                                        @endforeach
                                        @for ($i=$cal[count($cal)-1]['day'];$i<6;$i++)
                                            <li class="col p-0"></li>
                                        @endfor
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                        
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </form>


                
            </div>

            
        </div>

        <!-- Jadwal Harian Accordion - Full width on mobile, 3 cols on large screens -->
        <div class="col-12 col-lg-3 mb-4">
            <form action="" method="post">
                
                <div class="accordion" id="accordionExample">
                    <div class="card m-0" id="heading0">
                    <div class="card-header d-flex align-items-center flex-column no-after">
                        <div class="row w-100">
                            <h2 class="m-0">
                                <button type="button" class="btn btn-block text-left pl-0 font-weight-bold"  data-toggle="collapse" data-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                    Jadwal Harian
                                </button>
                            </h2>
                        </div>

                        <hr style="width: 250%;transform: translateX(-50%)">
                        <div class="row w-100 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="form-check">
                                <label for="" class="font-weight-normal m-0 btn">Select All</label>
                            </div>
                            <div>
                                <select name="" id="" class="form-control form-control-sm">
                                    <option value="">--Pilih Jenjang--</option>
                                    <option value="1">Jenjang 1</option>
                                    <option value="2">Jenjang 2</option>
                                    <option value="3">Jenjang 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="collapse" id="collapse0" aria-labelledby="heading0" data-parent="#accordionExample">
                        <div class="card-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero laudantium eos, ipsum nesciunt quis hic?
                        </div>
                    </div>
                </div>
                    <div class="card m-0" id="heading1">
                        <div class="card-header no-after d-flex align-items-center">
                            <input type="checkbox" name="" id="" class="form-check">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link btn-block text-left mb-0"  data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    Senin
                                </button>
                            </h2>
                        </div>
                        <div class="collapse show" id="collapse1" aria-labelledby="heading1" data-parent="#accordionExample">
                            <div class="card-body">
                                <label for="">Jadwal</label>
                                <div class="form-row align-items-end">
                                    <div class="col">
                                        <label for="" class="font-weight-normal">Start</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="" class="font-weight-normal">End</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-pill btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card m-0" id="heading2">
                        <div class="card-header no-after d-flex align-items-center">
                            <input type="checkbox" name="" id="" class="form-check">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link btn-block text-left"  data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                    Selasa
                                </button>
                            </h2>
                        </div>
                        <div class="collapse" id="collapse2" aria-labelledby="heading2" data-parent="#accordionExample">
                            <div class="card-body">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero laudantium eos, ipsum nesciunt quis hic?
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Alerts Section - Full width on mobile, 3 cols on large screens -->
        <div class="col-12 col-lg-3 px-lg-0 mb-4">
            <ul class="list-unstyled">
                <li>
                    <div class="alert alert-info alert-dismissible fade show" >
                        <h4 class="alert-heading">Coba Coba</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, dolores. Odio cum impedit molestias totam?</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                </li>
                <li>
                    <form action="" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header px-0">
                                <h2 class="h5 ml-3 font-weight-bold">Tambah hari libur</h2>
                            </div>
                            <div class="card-body p-0">
                                <div class="form-group mb-0">
                                    <textarea type="text" class="form-control form-control-lg rounded-0 border-0" placeholder="input keterangan" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="input-group d-flex justify-content-end">
                                    <div class="input-group-prepend">
                                        <div class="dropdown">
                                            <button class="btn bg-white border border-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Pilih Jenjang
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-item">
                                                    <input type="checkbox">
                                                    <label for="" class="m-0 font-weight-normal ml-2">Semua Jenjang</label>
                                                </li>
                                                <li class="dropdown-item">
                                                    <input type="checkbox">
                                                    <label for="" class="m-0 font-weight-normal ml-2">Jenjang 1</label>
                                                </li>
                                                <li class="dropdown-item">
                                                    <input type="checkbox">
                                                    <label for="" class="m-0 font-weight-normal ml-2">Jenjang 2</label>
                                                </li>
                                                <li class="dropdown-item">
                                                    <input type="checkbox">
                                                    <label for="" class="m-0 font-weight-normal ml-2">Jenjang 3</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i> Tambah

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>



@endsection
