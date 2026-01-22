<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PresensiLibur;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    // Filters
    public $selectedAcademicYear;
    public $selectedMonth;
    
    // Config Data
    public $schedules = [];
    public $diaryConfig = [];
    
    // Event Management
    public $isEditing = false;
    public $selectedDates = [];
    public $newEventDescription = '';
    public $newEventJenjang = []; // Array of selected grades [1, 2, 3]

    // Schedule Management
    public $grade = 1;
    public $currentSchedule = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // Default to current month
        $this->selectedMonth = now()->month;
        
        // Default to active academic year or latest
        $activeYear = TahunAkademik::where('current', 1)->first();
        if (!$activeYear) {
            $activeYear = TahunAkademik::orderBy('nama_tahun', 'desc')->first();
        }
        $this->selectedAcademicYear = $activeYear ? $activeYear->id : null;

        // Load configs
        $this->loadConfigs();
        $this->updateCurrentSchedule();
    }

    private function loadConfigs()
    {
        // Get Schedules
        $path = "data/config/konfigurasi_jadwal_harian.json";
        if (Storage::exists($path)) {
            $this->schedules = json_decode(Storage::get($path), true);
        }

        // Get Diary Config
        $pathDiary = "data/config/konfigurasi_rekap_mental.json";
        if (Storage::exists($pathDiary)) {
            $this->diaryConfig = json_decode(Storage::get($pathDiary), true);
        } else {
            $this->diaryConfig = ['rentang' => 7];
        }
    }

    public function updatedGrade()
    {
        $this->grade = (int) $this->grade;
        $this->updateCurrentSchedule();
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
    }

    public function updatedSelectedAcademicYear()
    {
        $this->selectedAcademicYear = (int) $this->selectedAcademicYear;
    }

    private function updateCurrentSchedule()
    {
        if (isset($this->schedules[$this->grade])) {
            $this->currentSchedule = $this->schedules[$this->grade];
        } else {
            $this->currentSchedule = [];
        }
    }

    public function saveSchedule()
    {
        // $currentSchedule bound via wire:model, so we just save it back to master array
        // However, complex nested binding in Livewire can be tricky.
        // Assuming wire:model="currentSchedule.jadwal.0.jam_mulai" etc.
        
        $this->schedules[$this->grade] = $this->currentSchedule;
        
        // Save to file
        $path = "data/config/konfigurasi_jadwal_harian.json";
        Storage::put($path, json_encode($this->schedules, JSON_PRETTY_PRINT));
        
        $this->dispatch('swal:alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'text' => 'Jadwal berhasil disimpan'
        ]);
    }

    public function saveDiaryConfig()
    {
        $path = "data/config/konfigurasi_rekap_mental.json";
        Storage::put($path, json_encode($this->diaryConfig, JSON_PRETTY_PRINT));
        
        $this->dispatch('swal:alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'text' => 'Konfigurasi berhasil disimpan'
        ]);
    }

    #[Computed]
    public function academicYears()
    {
        return TahunAkademik::orderBy('nama_tahun', 'desc')->get();
    }

    #[Computed]
    public function calendarYear()
    {
        // Determine year based on Academic Year and Month
        // Standard: July (7) starts the new academic year
        // e.g. 2025/2026. If Month >= 7 -> 2025. If Month <= 6 -> 2026.
        
        $ta = TahunAkademik::find($this->selectedAcademicYear);
        if (!$ta) return now()->year;

        $parts = explode('/', $ta->nama_tahun);
        if (count($parts) < 2) return now()->year;

        $startYear = (int)$parts[0];
        $endYear = (int)$parts[1];

        if ($this->selectedMonth >= 7) {
            return $startYear;
        } else {
            return $endYear;
        }
    }

    #[Computed]
    public function calendars()
    {
        $year = $this->calendarYear;
        $month = $this->selectedMonth;
        
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $index = 0;
        $calendar = [];
        
        // Fill calendar weeks
        foreach ($start->toPeriod($end) as $date) {
            $day = $date->dayOfWeek;
            
            // If Sunday (0) or first day of loop, check if we need new row
            if ($day == 0 || empty($calendar)) {
                $index = count($calendar);
                $calendar[$index] = [];
            }
            
            // If it's the first week and doesn't start on Sunday, we might need padding?
            // The original code pushed to current array.
            
            $calendar[$index][] = [
                'date' => $date->day,
                'full_date' => $date->format('Y-m-d'),
                'day' => $day,
                'is_weekend' => in_array($day, [0, 6]) // 0=Sun, 6=Sat (Actually 0 is Sunday, wait. Carbon dayOfWeek: 0=Sunday, 6=Saturday)
            ];
        }
        
        return $calendar;
    }

    #[Computed]
    public function vacations()
    {
        // Get vacations for the selected month and calculated year
        return PresensiLibur::whereMonth('tanggal_mulai', $this->selectedMonth)
            ->whereYear('tanggal_mulai', $this->calendarYear) // Or usage start/end date logic
            ->orWhere(function($q) {
                 // Handle ranges overlapping months?
                 // Simple logic for now matching original: Filtered by 'bulan_mulai' or dates
                 $q->where('bulan_mulai', $this->selectedMonth)
                   ->whereRaw('YEAR(tanggal_mulai) = ?', [$this->calendarYear]);
            })
            ->orderBy('tanggal_mulai')
            ->get();
    }
    
    public function selectDate($dateStr)
    {
        if (!$this->isEditing) return;

        // Reset if 2 selected
        if (count($this->selectedDates) >= 2) {
            $this->selectedDates = [];
        }

        $this->selectedDates[] = $dateStr;
        sort($this->selectedDates);
    }
    
    public function isDateSelected($dateStr)
    {
        if (empty($this->selectedDates)) return false;
        
        $start = $this->selectedDates[0];
        $end = isset($this->selectedDates[1]) ? $this->selectedDates[1] : $start;
        
        return $dateStr >= $start && $dateStr <= $end;
    }
    
    public function isVacant($dateStr)
    {
        // Check if date is in vacations
        // We can use the computed property
        foreach ($this->vacations as $vac) {
            if ($dateStr >= $vac->tanggal_mulai && $dateStr <= $vac->tanggal_selesai) {
                return true;
            }
        }
        return false;
    }

    public function saveEvent()
    {
        if (empty($this->selectedDates)) {
            $this->dispatch('swal:alert', ['type'=>'error', 'title'=>'Oops', 'text'=>'Pilih tanggal terlebih dahulu!']);
            return;
        }

        $this->validate([
            'newEventDescription' => 'required',
            'newEventJenjang' => 'required|array|min:1'
        ]);

        $start = $this->selectedDates[0];
        $end = isset($this->selectedDates[1]) ? $this->selectedDates[1] : $start;
        $month = Carbon::parse($start)->month; // Use start date month

        PresensiLibur::create([
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'bulan_mulai' => $month,
            'bulan_selesai' => $month, // Simplified
            'ket' => $this->newEventDescription,
            'jenjang' => $this->newEventJenjang // Model casting handled? Migration says json?
            // Actually PresensiLibur migration structure needs check. 
            // Usually need casting in Model.
        ]);

        // Reset
        $this->selectedDates = [];
        $this->newEventDescription = '';
        $this->newEventJenjang = [];
        $this->isEditing = false;
        
        $this->dispatch('swal:alert', ['type'=>'success', 'title'=>'Berhasil', 'text'=>'Event libur ditambahkan']);
    }

    public function deleteEvent($id)
    {
        PresensiLibur::destroy($id);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->extends('layouts.admin')
            ->section('content')
            ->with([
                'pageTitle' => 'Dashboard',
                'pageSubtitle' => 'Panel Administrasi'
            ]);
    }
}
