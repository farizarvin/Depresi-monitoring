<tbody>
    {{ dd($siswa) }}
    @foreach ($siswa as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nisn}}</td>
            <td>{{ $row->nama_lengkap }}</td>
            <td>{{ $row->gender ? "Laki-laki" : "Perempuan" }}</td>
            <td>{{ $row->getKelasAktif()->nama }}</td>
            <td>{{ $row->status ? 'Aktif' : 'Non-aktif' }}</td>
            <td class="d-flex justify-content-around">
                <a 
                href="#" 
                role="button"
                class="btn btn-warning btn-xs"
                x-on:click="event.preventDefault();"
                onclick="Livewire.dispatch('siswa:edit', {id: {{ $row->id }}})"
                data-toggle="modal"
                data-target="#edit-modal"
                >
                    <i class="fas fa-edit"></i>
                </a>
                <a 
                href="#" 
                role="button"
                class="btn btn-danger btn-xs"
                x-on:click="event.preventDefault();"
                onclick="Livewire.dispatch('swal:confirm', {
                    title : 'Konfirmasi hapus data',
                    text : 'Apakah anda yakin ingin menghapus siswa ini?',
                    icon : 'warning',
                    method : 'siswa:delete',
                    params : {id : {{ $row->id }}}
                })"
                >
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
</tbody>
