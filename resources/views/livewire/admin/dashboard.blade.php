<div>
    <div class="row justify-content-center g-4">
        
        <!-- Header / Filters -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h1 class="h4 m-0 text-gray-800">Dashboard</h1>
                        <p class="m-0 text-muted small">Panel Administrasi</p>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Academic Year Filter -->
                        <select wire:model.live="selectedAcademicYear" class="form-select w-auto">
                            @foreach($this->academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->nama_tahun }} {{ $year->current ? '(Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Month Filter -->
                        <select wire:model.live="selectedMonth" class="form-select w-auto">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Column -->
        <div class="col-12 col-md-4 col-xl-3 mb-4">
            <form wire:submit="saveSchedule">
                <div class="accordion accordion-flush border" id="accordionSchedule">
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-success bg-gradient">
                            <button class="accordion-button fs-5 fw-bold text-primary py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                Jadwal Harian
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionSchedule">
                            <div class="accordion-body">
                                Atur jadwal masuk dan pulang sekolah untuk setiap jenjang.
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-3">
                            <div class="w-100">
                                <select wire:model.live="grade" class="form-select form-select-sm bg-light">
                                    <option value="1">Jenjang 1</option>
                                    <option value="2">Jenjang 2</option>
                                    <option value="3">Jenjang 3</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @if(!empty($currentSchedule))
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $key => $dayName)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDay{{ $key }}">
                                        <!-- Checkbox for holiday? Data structure dependent -->
                                        <!-- Assuming structure: hari_libur array of ints -->
                                        <div class="form-check me-2" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input" 
                                                   wire:model="currentSchedule.hari_libur" 
                                                   value="{{ $key }}">
                                        </div>
                                        <span class="fs-6 fw-medium text-black-50">{{ $dayName }}</span>
                                    </button>
                                </h2>
                                <div id="collapseDay{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionSchedule" wire:ignore.self>
                                    <div class="accordion-body bg-light-subtle">
                                        <div class="row align-items-end justify-content-e px-2">
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6"><small>Mulai</small></label>
                                                <input type="time" class="form-control rounded-end-0 border-end-0"
                                                       wire:model="currentSchedule.jadwal.{{ $key }}.jam_mulai">
                                            </div>
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6"><small>Akhir</small></label>
                                                <input type="time" class="form-control rounded-0 border-end-0"
                                                       wire:model="currentSchedule.jadwal.{{ $key }}.jam_akhir">
                                            </div>
                                            <div class="w-auto p-0">
                                                <button type="submit" class="btn btn-pill btn-primary rounded-start-0">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </form>
        </div>

        <!-- Calendar Column -->
        <div class="col-12 col-md-8 col-xl-6 mb-4">
            <div class="card">
                <div class="card-header py-0 no-after d-flex justify-content-between align-items-center bg-success bg-gradient bg-opacity-50">
                    <div class="py-4">
                        <h1 class="fs-5 m-0 d-block text-black-50">
                            <strong class="text-black-50">{{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }}</strong> 
                            - <span class="font-weight-normal text-black-50">{{ $this->calendarYear }}</span>
                        </h1>
                    </div>
                    
                    <div class="form-group d-flex m-0">
                        <button type="button" wire:click="$toggle('isEditing')" class="btn btn-sm {{ $isEditing ? 'btn-danger' : 'btn-warning' }}">
                            <i class="fas {{ $isEditing ? 'fa-times' : 'fa-edit' }}"></i> {{ $isEditing ? 'Cancel' : 'Edit' }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="table-layout: fixed;">
                            <thead class="bg-light">
                                <tr>
                                    @foreach(['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $i => $header)
                                        <th class="col text-center {{ in_array($i, [0, 6]) ? 'text-secondary' : '' }}">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->calendars as $week)
                                    <tr>
                                        {{-- Empty cells logic handled in backend? No, backend returns fully populated weeks? 
                                             Wait, my backend logic just pushes valid dates.
                                             If first week doesn't start on Sunday, first few cells are missing?
                                             Let's re-check backend. 
                                             Ah, "array_push($calendar[$index], ...)"
                                             If first day is Tuesday (2), then index 0 has item.
                                             Front-end needs to pad.
                                             OR Backend should pad.
                                             Let's pad in frontend for now to be safe, or check first item "day" value.
                                        --}}
                                        @if($loop->first && isset($week[0]['day']) && $week[0]['day'] > 0)
                                            @for($x = 0; $x < $week[0]['day']; $x++)
                                                <td class="col p-0 bg-light"></td>
                                            @endfor
                                        @endif

                                        @foreach($week as $day)
                                            <td class="col p-0" style="box-sizing: border-box;">
                                                @php
                                                    $dateStr = sprintf('%04d-%02d-%02d', $this->calendarYear, $selectedMonth, $day['date']);
                                                    $isVacant = $this->isVacant($dateStr); // N+1? No, iterates collections in memory
                                                    $isSelected = $this->isDateSelected($dateStr);
                                                    $isWeekend = in_array($day['day'], [0, 6]);
                                                @endphp

                                                @if($isWeekend)
                                                    <button type="button" class="w-100 h-100 px-0 rounded-0 border-0 position-relative btn btn-outline-light text-muted" disabled>
                                                        <div class="{{ $isVacant ? 'bg-warning-subtle' : '' }}">{{ $day['date'] }}</div>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            wire:click="selectDate('{{ $dateStr }}')"
                                                            class="w-100 h-100 px-0 rounded-0 border-0 position-relative {{ $isSelected ? 'btn bg-light' : 'btn btn-outline-light text-body' }}">
                                                        <div class="{{ $isVacant ? 'bg-warning-subtle' : '' }}">{{ $day['date'] }}</div>
                                                    </button>
                                                @endif
                                            </td>
                                        @endforeach

                                        {{-- Pad end --}}
                                        @php 
                                            $lastDay = end($week)['day'];
                                        @endphp
                                        @if($lastDay < 6)
                                            @for($x = $lastDay + 1; $x <= 6; $x++)
                                                <td class="col p-0 bg-light"></td>
                                            @endfor
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Event List -->
                    <ul class="list-unstyled mt-3">
                        @foreach($this->vacations as $vac)
                            <li class="list-item mb-2">
                                <div class="card bg-warning-subtle text-sm">
                                    <div class="card-body no-after d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="m-0 font-weight-semibold text-black-50" style="font-size: 14px;">
                                                <span class="fw-bold">{{ \Carbon\Carbon::parse($vac->tanggal_mulai)->format('d M') }} 
                                                @if($vac->tanggal_mulai != $vac->tanggal_selesai)
                                                    - {{ \Carbon\Carbon::parse($vac->tanggal_selesai)->format('d M') }}
                                                @endif
                                                </span> 
                                                : {{ $vac->ket }}
                                            </p>
                                        </div>
                                        <button wire:click="deleteEvent({{ $vac->id }})" class="text-black-50 btn btn-sm"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add Event & Config Column -->
        <div class="col-12 col-md-12 col-xl-3 mb-4">
            <!-- Add Event Form -->
            <form wire:submit="saveEvent">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-4 bg-warning bg-gradient bg-opacity-50">
                        <h2 class="fs-5 fw-medium m-0 text-black-50">Add Event</h2>
                    </div>
                    <div class="card-body">
                        @if(empty($selectedDates))
                            <div class="alert alert-info py-2 small">
                                <i class="fas fa-info-circle"></i> Klik tombol <b>Edit</b> di kalender lalu pilih tanggal (range).
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tanggal Terpilih:</label>
                                <div class="form-control form-control-sm bg-light">
                                    {{ \Carbon\Carbon::parse($selectedDates[0])->format('d M Y') }}
                                    @if(count($selectedDates) > 1)
                                        s/d {{ \Carbon\Carbon::parse($selectedDates[1])->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-medium"><small>Keterangan Event</small></label>
                            <textarea wire:model="newEventDescription" rows="3" class="form-control" placeholder="Deskripsi event..."></textarea>
                            @error('newEventDescription') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium d-block"><small>Jenjang</small></label>
                            <div class="d-flex gap-3">
                                @foreach([1,2,3] as $j)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="newEventJenjang" value="{{ $j }}" id="j{{ $j }}">
                                        <label class="form-check-label" for="j{{ $j }}">{{ $j }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('newEventJenjang') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100" {{ empty($selectedDates) ? 'disabled' : '' }}>Tambah</button>
                    </div>
                </div>
            </form>

            <!-- Config Form -->
            <form wire:submit="saveDiaryConfig">
                <div class="card shadow-sm">
                    <div class="card-header py-4 bg-info bg-gradient bg-opacity-50">
                        <h2 class="fs-5 fw-medium m-0 text-black-50">Rekap Range</h2>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-medium"><small>Rentang Hari</small></label>
                        <div class="input-group">
                            <input type="number" wire:model="diaryConfig.rentang" class="form-control">
                            <div class="input-group-text fs-6"><small>days</small></div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
