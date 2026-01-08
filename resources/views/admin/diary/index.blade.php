

@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Dashboard Mental Siswa</h1>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    const labels = [["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"]];
    const moodData = [1, 1, 1, 1, 1, 1]; // tanggal berurutan
    const emotionLabels = {
        1: "sadness",
        2: "anger",
        3: "fear",
        4: "disgust",
        5: "happy",
        6: "surprise"
    };
    const emotionLabels2 = Object.fromEntries(Object.entries(emotionLabels).map(([key, value]) => [value, key]))
    function loadPaginatedData(history, page=1)
    {
        // const end=10*page;
        // const start=10*(page-1);
        // const slicedHistory=history.slice(start, end)
        const data=[];
        const xLabels=[];

        history.forEach(e=>{
            data.push(emotionLabels2[e.swafoto_pred])
            xLabels.push(" ");
        })

        loadDiagram(data, xLabels)
    }
    function loadDiagram(data, xLabels)
    {
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: xLabels,
                datasets: [{
                    label: 'Mood',
                    data: data,
                    borderWidth: 2,
                    borderColor: "rgba(60,141,188,0.8)",
                    backgroundColor: "rgba(60,141,188,0.2)",
                    fill: false,
                    // garis patah-patah
                    tension: 0 // biar benar-benar patah
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes : [{
                        display:true,
                        
                        position: 'bottom',
                        ticks: {
                            min: 0,
                            max: 6,
                            step: 1,
                            callback: function (value) {
                                return emotionLabels[value];
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Moods'
                        }
                    }]
                }
            }
        });
    }

    loadDiagram(moodData, labels);
</script>


@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-11">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary bg-gradient bg-opacity-50 no-after p-4">
                <h6 class="m-0 fw-medium fs-5 text-black-50 mb-3">Mental Siswa</h6>
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau NISN..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-6 col-md-3">
                        <select name="class" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $classFilter == $class->id ? 'selected' : '' }}>
                                    {{ $class->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5 col-md-3">
                        <select name="year" class="form-select">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $academicYear == $year->id ? 'selected' : '' }}>
                                    {{ $year->nama_tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1 col-md-1">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table border">
                        <thead class="table-light">
                            <tr>
                                <th class="col-1 py-3 fw-medium text-center">No</th>
                                <th class="col-3 py-3 fw-medium">Siswa</th>
                                <th class="col-1 py-3 fw-medium">Kelas</th>
                                <th class="col-4 py-3 fw-medium">Tingkat Depresi</th>
                                <th class="col-2 py-3 fw-medium">Label</th>
                                <th scope="col" class="col-1 py-3 fw-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-divide">
                            @forelse ($students as $key=>$student)
                                @php
                                    $depressionRate=$student->mental_health->get('result')->get('depression_rate') ?? 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>
                                        <small>
                                            <div class="fw-medium">{{ $student->nama_lengkap }}</div>
                                            <div class="text-secondary">{{ $student->nisn }}</div>
                                        </small>
                                    </td>
                                    <td>
                                        {{ $student->activeClass->first()?->nama ?? "-" }}
                                    </td>
                                    <td class="alignment-end">
                                        <div 
                                        class="progress rounded-pill w-75" 
                                        style="height: 10px;">
                                            <div 
                                            role="progressbar" 
                                            class="progress-bar {{ $depressionRate < $threshold ? 'bg-success' : 'bg-danger' }} bg-gradient bg-opacity-75" 
                                            style="width: {{ $depressionRate }}%;"
                                            aria-valuenow="{{ $depressionRate }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="w-75 d-flex flex-wrap mt-1" style="font-size: 12px;">
                                            <div class="fw-medium" style="width: {{ $depressionRate }}%;min-width: fit-content">
                                                <small>{{ round($depressionRate, 2) }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($depressionRate < $threshold)
                                        <div class="badge badge-sm bg-success bg-gradient bg-opacity-75 rounded-pill">Normal</div>
                                        @else
                                        <div class="badge badge-sm bg-danger bg-gradient bg-opacity-75 rounded-pill">Depresi</div>
                                        @endif
                                    </td>
                                    <td>
                                        <button 
                                        class="btn btn-sm btn-primary"
                                        onclick='setDetailedView({{ $student }}, {{ $student->mental_health->get("detail") }})'
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="bg-light-subtle text-center text-black-50 py-4">
                                        @if($search || $classFilter)
                                            <i class="fas fa-search fa-2x mb-2 text-muted"></i>
                                            <div>Tidak ditemukan data siswa yang sesuai</div>
                                            <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <div>Belum ada data siswa</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light-subtle border-top d-flex justify-content-between no-after py-0 px-4">
                <div class="d-flex justify-content-end py-4 w-100">
                    {{ $students->links() }}
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

<!-- Modal for Mental Health Details -->
<div class="modal fade" id="modalMentalDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-gradient bg-opacity-50">
                <h5 class="modal-title">Detail Mental Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Student Profile Card -->
                    <div class="col-12 mb-3">
                        <div class="card bg-success bg-gradient bg-opacity-50">
                            <div class="card-body d-flex align-items-center gap-3">
                                <img id="modalStudentAvatar" src="" alt="Avatar" 
                                     class="rounded-circle border border-2 border-white" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h5 class="fw-bold text-white mb-1" id="modalStudentName">-</h5>
                                    <p class="text-light mb-0" id="modalStudentNisn">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mood Chart -->
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card">
                            <div class="card-header bg-info bg-opacity-10">
                                <h6 class="fw-bold mb-0"><i class="fas fa-chart-line me-2"></i>Diagram Mental</h6>
                            </div>
                            <div class="card-body" style="height: 350px; position: relative;">
                                <canvas id="modalMoodChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Mental History Table -->
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="fw-bold mb-0"><i class="fas fa-history me-2"></i>Riwayat Mental</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">No</th>
                                                <th>Tanggal</th>
                                                <th>Mood</th>
                                                <th>Prediksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalHistoryTableBody">
                                            <!-- Populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                                <div id="modalHistoryEmpty" class="text-center py-4 d-none">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Tidak ada riwayat mental</p>
                                </div>
                            </div>
                        </div>
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
    let modalMoodChart = null;
    
    function setDetailedView(student, history)
    {
        // Update student info
        document.getElementById('modalStudentName').textContent = student.nama_lengkap;
        document.getElementById('modalStudentNisn').textContent = `NISN: ${student.nisn}`;
        
        const avatarUrl = student.user?.avatar_url 
            ? `http://localhost:8000/files/images/users/id/${student.id_user}/${student.user.avatar_url}`
            : 'http://localhost:8000/files/images/users/default';
        document.getElementById('modalStudentAvatar').src = avatarUrl;
        
        // Populate history table
        const historyTableBody = document.getElementById('modalHistoryTableBody');
        const historyEmpty = document.getElementById('modalHistoryEmpty');
        
        if (history && history.length > 0) {
            historyTableBody.parentElement.parentElement.classList.remove('d-none');
            historyEmpty.classList.add('d-none');
            
            historyTableBody.innerHTML = history.map((item, index) => {
                const date = new Date(item.waktu).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const moodBadge = getMoodBadge(item.swafoto_pred);
                const predictionBadge = item.catatan_pred === 'Terindikasi Depresi' 
                    ? '<span class="badge bg-danger">Terindikasi Depresi</span>'
                    : '<span class="badge bg-success">Normal</span>';
                
                return `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td><small>${date}</small></td>
                        <td>${moodBadge}</td>
                        <td>${predictionBadge}</td>
                    </tr>
                `;
            }).join('');
        } else {
            historyTableBody.parentElement.parentElement.classList.add('d-none');
            historyEmpty.classList.remove('d-none');
        }
        
        // Load mood chart
        loadMoodChart(history || []);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('modalMentalDetails'));
        modal.show();
    }
    
    function getMoodBadge(mood) {
        const badges = {
            'happy': '<span class="badge bg-success">😊 Happy</span>',
            'sadness': '<span class="badge bg-primary">😢 Sadness</span>',
            'anger': '<span class="badge bg-danger">😠 Anger</span>',
            'fear': '<span class="badge bg-warning">😨 Fear</span>',
            'disgust': '<span class="badge bg-secondary">🤢 Disgust</span>',
            'surprise': '<span class="badge bg-info">😲 Surprise</span>'
        };
        return badges[mood?.toLowerCase()] || mood;
    }
    
    function loadMoodChart(history) {
        console.log('loadMoodChart called, history items:', history.length);
        const emotionLabels = {
            1: "sadness",
            2: "anger",
            3: "fear",
            4: "disgust",
            5: "happy",
            6: "surprise"
        };
        const emotionLabels2 = Object.fromEntries(Object.entries(emotionLabels).map(([key, value]) => [value, key]));
        
        const data = [];
        const xLabels = [];
        
        history.forEach(e => {
            data.push(emotionLabels2[e.swafoto_pred?.toLowerCase()] || 0);
            const date = new Date(e.waktu);
            xLabels.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
        });
        
        console.log('Chart data prepared:', { dataPoints: data.length, labels: xLabels.length });
        
        const ctx = document.getElementById('modalMoodChart');
        if (!ctx) {
            console.error('Canvas modalMoodChart not found!');
            return;
        }
        const context = ctx.getContext('2d');
        
        // Destroy previous chart if exists
        if (modalMoodChart) {
            modalMoodChart.destroy();
        }
        
        modalMoodChart = new Chart(context, {
            type: 'line',
            data: {
                labels: xLabels,
                datasets: [{
                    label: 'Mood',
                    data: data,
                    borderWidth: 2,
                    borderColor: "rgba(60,141,188,0.8)",
                    backgroundColor: "rgba(60,141,188,0.2)",
                    fill: false,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            min: 0,
                            max: 6,
                            stepSize: 1,
                            callback: function (value) {
                                return emotionLabels[value] || '';
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Moods'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }]
                }
            }
        });
    }
</script>
@endsection
