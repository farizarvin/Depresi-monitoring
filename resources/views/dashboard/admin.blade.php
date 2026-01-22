@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@php
    $pageTitle = 'Dashboard';
    $pageSubtitle = 'Panel Administrasi';
@endphp

@section('content')
    
    <div 
    class="row justify-content-center g-4"
    x-data="{
        calendars : {{ Js::from($calendars) }},
        vacations : {{ Js::from($vacations) }}, 
        vacation : {}, 
        current : 0, 
        index : 0,
        loading : true,
        isEditing : false,
        selectedDates : [],
        async initData() 
        {
            

            
        },
        async showEvents(date)
        {
            const container=Alpine.$data(document.querySelector(`[x-ref='events_container']`))

            const lists=await this.vacations.filter(e=>{
                const bulanMulai=e.bulan_mulai;
                const bulanAkhir=e.bulan_selesai;
                const now=date * bulanMulai;
                return e.tanggal_mulai <= date && e.tanggal_selesai >= date;
            })
            

            const day=String(date).padStart(2, '0')
            const month=String(new Date().getMonth()+1).padStart(2, '0')
            container.events=lists
            container.date=`${day}-${month}`
        },
        chooseDate(date)
        {
        
            if(!this.isEditing) return
            if(this.selectedDates.length >= 2)
            {
                this.selectedDates=[]
                this.selectedDates.push(date)
                return;
            }
            this.selectedDates.push(date)
        
            this.selectedDates.sort((a,b)=>a - b);
        },
        isChosen(date)
        {
            const start = this.selectedDates[0];
            const end = this.selectedDates[1] || start;
            const chosen=start <= date && end >= date;
            return chosen;
        async submitEventForm(event) {
            const form = event.target;
            const month = new Date().getMonth() + 1;
            
            // Validate selection
            if(this.selectedDates.length === 0) {
                // Optional: Alert user to select date
                return;
            }

            document.getElementById('tanggal_mulai').value = this.selectedDates[0];
            document.getElementById('tanggal_selesai').value = this.selectedDates[1] || this.selectedDates[0];
            document.getElementById('bulan_mulai').value = month;
            document.getElementById('bulan_selesai').value = month;

            form.submit();
        },
        checkVacant(date) {
            if(!this.vacations || !Array.isArray(this.vacations)) {
                console.warn('Vacations data missing or invalid');
                return false;
            }
            return this.vacations.some(vacation => {
                return (vacation.tanggal_mulai <= date && date <= vacation.tanggal_selesai);
            });
        },
        handleDateClick(date) {
            if(this.isEditing) {
                this.chooseDate(date);
            } else {
                this.showEvents(date);
            }
        }
    }"

    x-init="initData"
    x-ref="dates_container">
        <!-- Schedule Column -->
        <div class="col-12 col-md-4 col-xl-3 mb-4">
            <form action="{{ route('admin.jadwal-harian.update') }}" method="post">
            @csrf
                <div 
                id="accordionExample" 
                class="accordion accordion-flush border" 
                x-data="{ 
                    data : {{ Js::from($schedules) }}, 
                    grade : 1,
                    schedule : [],
                    days : {
                        0 : 'Senin', 
                        1 : 'Selasa',
                        2 : 'Rabu', 
                        3 : 'Kamis', 
                        4 : 'Jumat',
                        5 : 'Sabtu',
                        6 : 'Minggu'
                    },
                    setSchedule() 
                    {
                        this.schedule=this.data[this.grade]
                        console.log(this.schedule);
                    },
                    async initData()
                    {
                        schedule=await this.data;
                        schedule=this.data[this.grade];
                        this.schedule=schedule;
                    }
                }"
                x-init="initData;$watch('grade', _=>setSchedule())"
                >
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-success bg-gradient">
                            <button class="accordion-button fs-5 fw-bold text-primary py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Jadwal Harian
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Atur jadwal masuk dan pulang sekolah untuk setiap jenjang.
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-3">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="form-check-input" id="selectAllWeek">
                                <label for="selectAllWeek" class="form-check-label ms-2 fw-medium cursor-pointer">Select All</label>
                            </div>
                            <div class="w-auto">
                                <select name="jenjang" class="form-select form-select-sm bg-light" x-model="grade" style="min-width: 120px;">
                                    <option value="">--Pilih Jenjang--</option>
                                    <template x-for="i in 3" :key="i">
                                        <option :value="i" :selected="i===parseInt(grade)" x-text="'Jenjang '+i"></option>
                                    </template>
                                </select>
                                <x-form-error-text :field="'jenjang'" />
                            </div>
                        </div>
                    </div>

                    {{-- <input type="hidden" name="hari_libur[0]"> --}}
                    <template x-if="schedule?.jadwal?.length > 0">
                        <template x-for="(day, key) in days" :key="day">
                            <div class="accordion-item" :id="'heading'+key">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+key" aria-expanded="true" :aria-controls="'collapse'+key">
                                        
                                        <input type="checkbox" class="form-check" :name="`hari_libur[${key}]`" :value="key" :checked="!schedule?.hari_libur.includes(parseInt(key))">
                                        <div type="button" class="fs-6 fw-medium ms-2 text-black-50"  x-text="day">
                                        
                                        </div>
                                    </button>
                                </h2>
                                <div  :id="'collapse'+key" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body bg-light-subtle">
                                        <div class="row align-items-end justify-content-e px-2">
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6">
                                                    <small>Mulai</small>
                                                </label>
                                                <input type="time" :name="`jadwal[${key}][jam_mulai]`" id="" class="form-control rounded-end-0 border-end-0" :value="schedule?.jadwal[key]?.jam_mulai">
                                            </div>
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6">
                                                    <small>Akhir</small>
                                                </label>
                                                <input type="time" :name="`jadwal[${key}][jam_akhir]`" id="" class="form-control rounded-0 border-end-0" :value="schedule?.jadwal[key]?.jam_akhir">
                                            </div>
                                            <div class="w-auto p-0">
                                                <button type="submit" class="btn btn-pill btn-primary rounded-start-0">Save</button>
                                            </div>
                                        </div>
                                        <x-form-error-text :field="'jadwal'" />
                                        <x-form-error-text :field="'jadwal'" />

                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    
                </div>
            </form>
        </div>

        <!-- Calendar Column -->
        <div class="col-12 col-md-8 col-xl-6 mb-4">
            <div 
            class="card"
            >
                <form 
                action="" 
                method="post">
                    @csrf
                    <div class="card-header py-0 no-after d-flex justify-content-between align-items-center bg-success bg-gradient bg-opacity-50">
                        <div class="py-4">
                            <h1 class="fs-5 m-0 d-block text-black-50">
                                <strong class="text-black-50">{{ now()->format('F')}}</strong> - <span class="font-weight-normal text-black-50">{{ now()->year }}</span>
                            </h1>
                        </div>
                        
                        <div class="form-group d-flex m-0">
                            <select 
                            id="" 
                            name="" 
                            class="form-select bg-secondary-subtle fw-medium opacity-75">
                                <option value="">Januari</option>
                            </select>

                            <button
                            type="button"
                            x-on:click="isEditing=!isEditing;"
                            class="btn btn-warning btn-sm"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="table-layout: fixed;">
                                <thead class="bg-light">
                                    @php
                                        $headers=['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
                                    @endphp

                                    <tr>
                                        @foreach ($headers as $key=>$header)
                                            <th 
                                            class="col text-center {{ in_array($key, [5, 6]) ? 'text-secondary' : '' }}">{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                        
                                    <template x-for="(week, weekIndex) in calendars" :key="weekIndex">
                                        <tr>
                                            <template x-for="i in week[0].day">
                                                <td class="col p-0 bg-light">
                                                    
                                                </td>
                                            </template>
                                        
                                            <template x-for="day in week" :key="day.date">
                                                <td class="col p-0" style="box-sizing: border-box;">
                                                    <template x-if="([5,6]).includes(day.day)">
                                                        <button 
                                                        type="button"
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative btn btn-outline-light text-muted" disabled
                                                        >
                                                            <div :class="{'bg-warning-subtle' : checkVacant(day.date)}" x-text="day.date">
                                                                
                                                            </div>
                                                        </button>
                                                    </template>

                                                    <template x-if="!([5,6]).includes(day.day)">
                                                        <button 
                                                        type="button"
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative text-body" 
                                                        :class="isChosen(day.date) ? 'btn bg-light' : 'btn btn-outline-light'"
                                                        x-on:click='handleDateClick(day.date)'
                                                        >
                                                            <div :class="{'bg-warning-subtle' : checkVacant(day.date)}" x-text="day.date">
                                                            </div>
                                                        </button>
                                                    </template>
                                                    
                                                </td>
                                            </template>

                                            <template x-for="_ in 6 - (week[week.length-1]['day'] || 0)">
                                                <td class="col p-0 bg-light"></td>
                                            </template>
                                        </tr>
                                    </template>
                                
                                    
                                    @php
                                        $current=0;
                                        $index=0;
                                    @endphp
                                </tbody>
                            </table>
                            <x-form-error-text :field="'id'" />
                            <x-form-error-text :field="'date'" />
                        </div>
                        </ul>
                        
                    </div>
                </form>
            </div>

            <ul class="list-unstyled mt-3" x-data="{events : [], index : 0, date : ''}" x-ref="events_container">
                <template x-for="item in events" :key="index++">
                    <li class="list-item mb-2">
                        <div class="card bg-warning-subtle text-sm">
                            <div class="card-body no-after d-flex justify-content-between align-items-center">
                                <p class="m-0 font-weight-semibold text-black-50" style="font-size: 14px;" x-text="item.ket"></p>
                                <form action="{{ route('admin.presensi-libur.destroy') }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="id" x-model="item.id">
                                    <input type="hidden" name="date" x-model="date">
                                    <button type="submit" class="text-black-50 btn"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
            
        </div>

        <!-- Application / Info Column -->
        <div class="col-12 col-md-12 col-xl-3 mb-4">
            <form action="{{ route('admin.presensi-libur.store') }}" method="post" 
            @submit.prevent="submitEventForm($event)">
                @csrf
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-4 bg-warning bg-gradient bg-opacity-50">
                        <h2 class="fs-5 fw-medium m-0 text-black-50">Add Event</h2>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="tanggal_mulai" id="tanggal_mulai">
                        <input type="hidden" name="tanggal_selesai" id="tanggal_selesai">
                        <input type="hidden" name="bulan_mulai" id="bulan_mulai">
                        <input type="hidden" name="bulan_selesai" id="bulan_selesai">
                        
                        <div class="mb-3">
                            <label for="ket_event" class="form-label fw-medium"><small>Keterangan Event</small></label>
                            <textarea  id="ket_event" cols="30" rows="5" class="form-control" name="ket" placeholder="Deskripsi event..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium d-block"><small>Jenjang</small></label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="jenjang1" name="jenjang[]" value="1">
                                    <label class="form-check-label" for="jenjang1">1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="jenjang2" name="jenjang[]" value="2">
                                    <label class="form-check-label" for="jenjang2">2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="jenjang3" name="jenjang[]" value="3">
                                    <label class="form-check-label" for="jenjang3">3</label>
                                </div>
                            </div>
                        </div>

                        <x-form-error-text :field="'jenjang'" />
                        <x-form-error-text :field="'tanggal_mulai'" />
                        <x-form-error-text :field="'tanggal_selesai'" />
                        <x-form-error-text :field="'bulan_mulai'" />
                        <x-form-error-text :field="'bulan_selesai'" />
                        <x-form-error-text :field="'ket'" />

                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.config.diary.update') }}" method="post">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header py-4 bg-info bg-gradient bg-opacity-50">
                        <h2 class="fs-5 fw-medium m-0 text-black-50">Rekap Range</h2>
                    </div>
                    <div class="card-body">
                        <label for="rentang_field" class="form-label fw-medium">
                            <small>Rentang Hari</small>
                        </label>
                        <div class="input-group">
                            <input type="number" id="rentang_field" name="rentang" value="{{ (int) ($diaryConfig['rentang'] ?? 7) }}" class="form-control">
                            <div class="input-group-text fs-6">
                                <small>days</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
@endsection
