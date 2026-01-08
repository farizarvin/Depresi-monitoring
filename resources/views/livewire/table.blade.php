<!-- resources/views/components/table-modern.blade.php -->
@props([
    'title'   => 'Data Table',
    'headers' => [],
    'addRoute' => null,  // route untuk tombol “Tambah”
])

<div class="row justify-content-center">
    <div class="col-10">
        <div class="card shadow-sm mb-4">
            <div class="card-header no-after py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>

                <div class="d-flex align-items-center">
                    <input type="text" name="" id="" class="form-control mr-2" placeholder="search items">
                    @if($addRoute)
                        <a 
                        href="#" 
                        class="btn btn-primary d-block d-flex align-items-center"
                        x-on:click="event.preventDefault();"
                        data-toggle="modal"
                        data-target="#create-modal">
                            <i class="fas fa-plus mr-2"></i> Tambah
                        </a>

                        @if (isset($create_form))
                            {{ $create_form }}
                        @endif

                        @if (isset($edit_form))
                            {{ $edit_form }}
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm">
                        <thead class="bg-light">
                            <tr>
                                @foreach($headers as $header)
                                    <th scope="col">{{ $header }}</th>
                                @endforeach
                                <th scope="col" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        {{ $slot }}
                    </table>

                    <div class="d-flex justify-content-left mt-4">
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
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>



