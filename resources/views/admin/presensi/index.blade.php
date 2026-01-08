

@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Riwayat Kehadiran</h1>
        </div>
    </div>
@endsection


@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header no-after p-4 d-flex align-items-center justify-content-between bg-success bg-gradient bg-opacity-50">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h2 class="fs-5 fw-medium text-black-50 m-0 col-5 col-md-4">Riwayat Kehadiran Siswa</h2>
                    <form method="GET" class="d-flex col-7 col-md-8 justify-content-end">
                        <div class="col-8 col-md-5">
                            <input type="text" name="search" class="form-control rounded-end-0 border-end-0 opacity-75" placeholder="search items" value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-4 col-md-5 d-flex">
                            <select name="class" class="form-select bg-light rounded-0 border-end-0 opacity-75">
                                <option value="">All Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ ($classFilter == $class->id) ? 'selected' : '' }}>
                                        {{ $class->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-select bg-light rounded-0 border-end-0 opacity-75">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ ($academicYear == $year->id) ? 'selected' : '' }}>
                                        {{ $year->nama_tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning rounded-start-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table border">
                        <thead class="table-light">
                            <tr>
                                <th class="col-1 py-3 fw-medium text-dark text-center">No</th>
                                <th class="col-3 py-3 fw-medium text-dark">Siswa</th>
                                <th class="col-1 py-3 fw-medium text-dark">Kelas</th>
                                <th class="col-5 py-3 fw-medium text-dark">Kehadiran</th>
                                <th scope="col" class="col-2 py-3 fw-medium text-dark">Aksi</th>
                            </tr>
                        </thead>
                        
                        @forelse ($studentAttendances as $key=>$studentAttendance)
                            @php
                                $persenHadir=0;
                                $persenAlpha=0;
                                $persenIjinSakit=0;
                                $details=$studentAttendance->presensi->get('details');
                                if($studentAttendance->presensi)
                                {
                                    $result=$studentAttendance->presensi->get('result');
                                    $persenHadir=$result?->persen_hadir ?? 0;
                                    $persenAlpha=$result?->persen_alpha ?? 0;
                                    $persenIjinSakit=$result?->persen_ijin_sakit ?? 0;
                                }
                            @endphp
                        
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>
                                    <small>
                                        <div class="fw-medium">{{$studentAttendance->nama_lengkap }}</div>
                                        <div class="text-secondary">{{$studentAttendance->nisn }}</div>
                                    </small>
                                </td>
                                <td>{{ $studentAttendance->classes->first()?->nama ?? "-" }}</td>
                                <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-success bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenHadir }}%;" 
                                        aria-valuenow="{{ $persenHadir }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>

                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-danger bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenAlpha }}%;" 
                                        aria-valuenow="{{ $persenAlpha }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>

                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-warning bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenIjinSakit }}%;" 
                                        aria-valuenow="{{ $persenIjinSakit }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>
                                    </div>
                                    
                                    <div class="w-75 d-flex flex-wrap text-xs mt-1" style="font-size: 12px;">
                                        @if($persenHadir > 0)
                                            <div class="fw-medium" style="width: {{ $persenHadir }}%;min-width: fit-content">
                                                <small>{{ $persenHadir }}%</small>
                                            </div>
                                        @endif
                                        @if($persenAlpha > 0)
                                            <div class="fw-medium" style="width: {{ $persenAlpha }}%;min-width: fit-content">
                                                <small>{{ $persenAlpha }}%</small>
                                            </div>
                                        @endif
                                        @if($persenIjinSakit > 0)
                                            <div class="fw-medium" style="width: {{ $persenIjinSakit }}%;min-width: fit-content">
                                                <small>{{ $persenIjinSakit }}%</small>
                                            </div>
                                        @endif

                                        
                                    </div>
                                </td>
                                <td>
                                    <a 
                                    href="#" 
                                    class="btn btn-sm btn-primary"
                                    x-on:click="event.preventDefault();"
                                    onclick="loadAttendancesHistory(`{{ route('admin.siswa.kehadiran.show', ['student'=>$studentAttendance->id, 'year'=>$academicYear]) }}`)">
                                    >
                                        <i class="fas fa-eye mr-2"></i> Details
                                    </a>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="bg-light-subtle text-center text-black-50">
                                    Belum Ada Siswa
                                </td>
                            </tr>
                        @endforelse
                    </table>

                    
                    
                </div>
            </div>
            <div class="card-footer bg-light-subtle border-top d-flex justify-content-between no-after p-4">
                <section class="d-flex">
                    <div>
                        <h3 class="h6 font-weight-bold">Ket</h3>
                        <ul class="list-unstyled font-italic fw-medium">
                            <li>
                                <span class="text-success mr-1"><i class="fas fa-circle"></i></span>
                                <small>Hadir</small>
                            </li>
                            <li>
                                <span class="text-danger mr-1"><i class="fas fa-circle"></i></span>
                                <small>Alpha</small>
                            </li>
                            <li>
                                <span class="text-warning mr-1"><i class="fas fa-circle"></i></span>
                                <small>Ijin/Sakit</small>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="h6 font-weight-bold">Total Pertemuan : <span class="font-italic">14</span></h3>
                    </div>
                </section>
                <div class="d-flex justify-content-left">
                    {{ $studentAttendances->links() }}
                    <div class="form-group m-0">
                        <select class="form-select bg-light rounded-start-0 border-start-0">
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

<!-- Modal untuk Detail Kehadiran Siswa -->
<div class="modal fade" id="modalAttendanceDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success bg-gradient bg-opacity-50">
                <h5 class="modal-title">Detail Kehadiran Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Student Info -->
                <div class="mb-4">
                    <h6 class="fw-bold" id="studentName">-</h6>
                    <small class="text-muted" id="studentNisn">-</small>
                </div>

                <!-- Attendance Records Section -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-calendar-check me-2"></i>Riwayat Presensi</h6>
                    <div id="attendanceTableContainer">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="attendanceEmptyState" class="text-center py-4 d-none">
                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                        <p class="text-muted">Tidak ada data presensi</p>
                    </div>
                </div>

                <hr>

                <!-- Diary Entries Section -->
                <div>
                    <h6 class="fw-bold mb-3"><i class="fas fa-book me-2"></i>Data Diary Siswa</h6>
                    <div id="diaryEntriesContainer">
                        <!-- Will be populated by JavaScript -->
                    </div>
                    <div id="diaryEmptyState" class="text-center py-4 d-none">
                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                        <p class="text-muted">Tidak ada data diary</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function loadAttendancesHistory(route)
    {
        try {
            const response = await fetch(route);
            const json = await response.json();

            console.log('Full API Response:', json);
            console.log('Response structure:', Object.keys(json));
            
            if (json.response) {
                const { student, attendances, diary_entries, has_attendances, has_diary } = json.response;
                
                console.log('Parsed data:', {
                    student,
                    attendances_count: attendances?.length,
                    diary_count: diary_entries?.length,
                    has_attendances,
                    has_diary
                });
                
                // Update student info
                document.getElementById('studentName').textContent = student.nama_lengkap;
                document.getElementById('studentNisn').textContent = `NISN: ${student.nisn}`;
                
                // Populate attendance table
                const attendanceTableBody = document.getElementById('attendanceTableBody');
                const attendanceTableContainer = document.getElementById('attendanceTableContainer');
                const attendanceEmptyState = document.getElementById('attendanceEmptyState');
                
                if (has_attendances) {
                    attendanceTableContainer.classList.remove('d-none');
                    attendanceEmptyState.classList.add('d-none');
                    
                    attendanceTableBody.innerHTML = attendances.map(attendance => {
                        const statusBadge = getStatusBadge(attendance.status);
                        const date = new Date(attendance.waktu).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        
                        // Find matching diary entry
                        const diaryEntry = diary_entries.find(d => d.presensi_waktu === attendance.waktu);
                        
                        // Hide keterangan/bukti for Hadir status
                        const keterangan = attendance.status !== 'H' ? (attendance.ket || '-') : '-';
                        const bukti = (attendance.status !== 'H' && attendance.doc) ? 
                            `<a href="/storage/${attendance.doc}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file"></i> Lihat
                            </a>` : '-';
                        
                        // Build diary row if exists
                        let diaryRow = '';
                        if (diaryEntry) {
                            const formPerasaan = diaryEntry.catatan || '-';
                            const ceritaPerasaan = diaryEntry.catatan_ket || '-';
                            diaryRow = `
                                <tr class="table-info bg-opacity-25">
                                    <td colspan="4" class="ps-4">
                                        <small>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <strong class="text-success"><i class="fas fa-smile me-1"></i>Bagaimana perasaan hari ini:</strong>
                                                    <div class="ms-3 mt-1">${formPerasaan}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong class="text-primary"><i class="fas fa-pen me-1"></i>Ceritakan perasaan hari ini:</strong>
                                                    <div class="ms-3 mt-1">${ceritaPerasaan}</div>
                                                </div>
                                            </div>
                                        </small>
                                    </td>
                                </tr>
                            `;
                        }
                        
                        return `
                            <tr>
                                <td>${date}</td>
                                <td>${statusBadge}</td>
                                <td><small>${keterangan}</small></td>
                                <td>${bukti}</td>
                            </tr>
                            ${diaryRow}
                        `;
                    }).join('');
                } else {
                    attendanceTableContainer.classList.add('d-none');
                    attendanceEmptyState.classList.remove('d-none');
                }
                
                // Populate diary entries
                const diaryEntriesContainer = document.getElementById('diaryEntriesContainer');
                const diaryEmptyState = document.getElementById('diaryEmptyState');
                
                if (has_diary) {
                    diaryEntriesContainer.classList.remove('d-none');
                    diaryEmptyState.classList.add('d-none');
                    
                    diaryEntriesContainer.innerHTML = diary_entries.map(diary => {
                        const date = new Date(diary.waktu).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        const formPerasaan = diary.catatan || 'Tidak ada data';
                        const ceritaPerasaan = diary.catatan_ket || 'Tidak ada data';
                        const prediction = diary.catatan_pred || diary.swafoto_pred;
                        
                        return `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i>${date}</small>
                                        ${prediction ? `<span class="badge bg-info">Prediksi: ${prediction}</span>` : ''}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="d-block text-success mb-1"><i class="fas fa-smile me-1"></i>Form Perasaan Hari Ini:</strong>
                                        <p class="mb-0 ms-3">${formPerasaan}</p>
                                    </div>
                                    <div>
                                        <strong class="d-block text-primary mb-1"><i class="fas fa-pen me-1"></i>Ceritakan Perasaan Hari Ini:</strong>
                                        <p class="mb-0 ms-3">${ceritaPerasaan}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    diaryEntriesContainer.classList.add('d-none');
                    diaryEmptyState.classList.remove('d-none');
                }
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('modalAttendanceDetails'));
                modal.show();
            }
        } catch (error) {
            console.error('Error loading attendance history:', error);
            alert('Gagal memuat data. Silakan coba lagi.');
        }
    }
    
    function getStatusBadge(status) {
        const badges = {
            'H': '<span class="badge bg-success">Hadir</span>',
            'S': '<span class="badge bg-warning">Sakit</span>',
            'I': '<span class="badge bg-info">Izin</span>',
            'A': '<span class="badge bg-danger">Alpha</span>'
        };
        return badges[status] || status;
    }
</script>
@endsection
