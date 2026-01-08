<!-- resources/views/components/table-modern.blade.php -->
@props([
    'title'   => 'Data Table',
    'headers' => [],
    'addRoute' => null,  // route untuk tombol “Tambah”
])

<div class="row justify-content-center w-100">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient bg-opacity-50 no-after p-4 d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-medium fs-5 text-black-50">{{ $title }}</h6>
                <div class="w-auto">
                    <div class="input-group">
                        <input type="text" class="form-control mr-2 opacity-75 bg-light" placeholder="search items">
                        @if($addRoute)
                            <a 
                            href="#" 
                            class="btn btn-warning d-block d-flex align-items-center"
                            x-on:click="event.preventDefault();"
                            onclick="Livewire.dispatch('{{ $addRoute }}')"
                            data-toggle="modal"
                            data-target="#create-modal">
                                <i class="fas fa-search mr-2"></i>
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
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-none">
                        <thead class="text-black-50">
                            <tr>
                                @foreach($headers as $header)
                                    <th scope="col" class="{{ $header=='No' ? 'text-center' : '' }} py-3 fw-medium">{{ $header }}</th>
                                @endforeach
                                <th scope="col" class="py-3 fw-medium" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        {{ $slot }}
                    </table>
                </div>
                
            </div>
            <div class="card-footer py-4 px-4">
                <div class="d-flex justify-content-left">
                    @if(isset($paginator))
                        {{ $paginator }}
                    @endif
                </div>
            </div>
        </div>
    </div>
<script>
    window.addEventListener('modal:close', event=>{
        $(`#${event.detail.modal_id}`).modal('hide')
    })
    function destroy()
    {
        
    }
    
</script>
</div>



