<tbody>
    @foreach ($kelas as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nama}}</td>
            <td>{{ $row->jenjang }}</td>
            <td>{{ $row->jurusan }}</td>
            <td class="d-flex justify-content-around">
                <a href="#" class="btn btn-warning btn-xs">
                    <i class="fas fa-eye"></i>
                </a>
                <a 
                href="#" 
                role="button"
                class="btn btn-info btn-xs"
                onclick="event.preventDefault();Livewire.dispatch('kelas-edit', {id : {{ $row->id }}})"
                data-toggle="modal"
                data-target="#edit-modal">
                    <i class="fas fa-edit"></i>
                </a>

                <a 
                href="#"
                role="button"
                class="btn btn-xs btn-danger"
                onclick="Livewire.dispatch('swal:confirm', {
                    title : 'Konfirmasi hapus data',
                    text : 'Apakah anda yakin ingin menghapus kelas ini?',
                    icon : 'warning',
                    method : 'kelas:delete',
                    params : {id : {{ $row->id }}}
                })">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
</tbody>
