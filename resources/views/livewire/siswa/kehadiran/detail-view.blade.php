<div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="view-modal-label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="h2" id="view-modal-label">Riwayat Kehadiran Siswa</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($presensi && $presensi->count())
                                @foreach ($presensi as $key=>$pr)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $pr->waktu }}</td>
                                        <td>{{ $pr->status }}</td>
                                        <td>
                                            <a 
                                            href="#" 
                                            class="btn btn-primary d-block d-flex align-items-center"
                                            x-on:click="event.preventDefault();"
                                            data-toggle="modal"
                                            data-target="#create-modal">
                                                <i class="fas fa-plus mr-2"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>