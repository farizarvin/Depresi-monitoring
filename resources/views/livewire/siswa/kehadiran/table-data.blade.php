<tbody class="table-divide">
    @forelse ($siswa as $key=>$sis)
        @php
            $persen_hadir=0;
            $persen_alpha=0;
            $persen_ijin_sakit=0;
            if(isset($presensi[$sis->id]))
            {
                $persen_hadir=$presensi[$sis->id]->persen_hadir;
                $persen_alpha=$presensi[$sis->id]->persen_alpha;
                $persen_ijin_sakit=$presensi[$sis->id]->persen_ijin_sakit;
            }
        @endphp
       
        <tr>
            <td class="">{{ $key+1 }}</td>
            <td>
                <div class="font-weight-bold">{{$sis->nama_lengkap }}</div>
                <div class="text-secondary">{{$sis->nisn }}</div>
            </td>
            <td>
                {{ $sis->getKelasByThak($thak->id)?->nama ?? "-" }}
            </td>
            <td class="alignment-end">
                <div class="progress rounded-pill w-75" style="height: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persen_hadir }}%;" aria-valuenow="{{ $persen_hadir }}" aria-valuemin="0" aria-valuemax="100">
                    
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persen_alpha }}%;" aria-valuenow="{{ $persen_alpha }}" aria-valuemin="0" aria-valuemax="100">

                    </div>
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persen_ijin_sakit }}%;" aria-valuenow="{{ $persen_ijin_sakit }}" aria-valuemin="0" aria-valuemax="100">

                    </div>
                </div>
                <div class="w-75 d-flex flex-wrap">
                    <div class="text-center" style="width: {{ $persen_hadir }}%;">
                        {{ $persen_hadir }}%
                    </div>
                    <div class="text-center" style="width: {{ $persen_alpha }}%;">
                        {{ $persen_alpha }}%
                    </div>
                    <div class="text-center" style="width: {{ $persen_ijin_sakit }}%;">
                        {{ $persen_ijin_sakit }}%
                    </div>
                </div>
            </td>
            <td>
                <a 
                href="#" 
                class="btn btn-sm btn-primary"
                x-on:click="event.preventDefault();"
                onclick="Livewire.dispatch('siswa_kehadiran:view', {id:{{ $sis->id }}})"
                data-toggle="modal"
                data-target="#view-modal">
                    <i class="fas fa-eye mr-2"></i> Details
                </a>

            </td>

        </tr>
    @empty
        
    @endforelse
</tbody>