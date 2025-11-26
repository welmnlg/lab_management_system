<?php

namespace App\Exports;

use App\Models\Logbook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LogbookExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Logbook::with(['user', 'room', 'course', 'schedule']);

        // Filter Period
        if (isset($this->filters['period'])) {
            if ($this->filters['period'] === 'week') {
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->filters['period'] === 'month') {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            } elseif ($this->filters['period'] === 'day') {
                $query->whereDate('date', now());
            }
        }

        // Advanced Filters
        if (!empty($this->filters['name'])) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->filters['name'] . '%');
            });
        }

        if (!empty($this->filters['nim'])) {
            $query->whereHas('user', function($q) {
                $q->where('nim', 'like', '%' . $this->filters['nim'] . '%');
            });
        }

        if (!empty($this->filters['course'])) {
            $query->whereHas('course', function($q) {
                $q->where('course_name', 'like', '%' . $this->filters['course'] . '%');
            });
        }

        if (!empty($this->filters['class'])) {
            $query->whereHas('schedule.courseClass', function($q) {
                $q->where('class_name', 'like', '%' . $this->filters['class'] . '%');
            });
        }

        if (!empty($this->filters['room'])) {
            $query->whereHas('room', function($q) {
                $q->where('room_name', 'like', '%' . $this->filters['room'] . '%');
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->orderBy('date', 'desc')->orderBy('login', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Lengkap',
            'NIM',
            'Mata Kuliah',
            'Kelas',
            'Ruangan',
            'Jadwal Kelas',
            'Log In',
            'Log Off',
            'Aktivitas',
            'Status',
        ];
    }

    public function map($logbook): array
    {
        return [
            $logbook->date->format('d/m/Y'),
            $logbook->user->name ?? '-',
            $logbook->user->nim ?? '-',
            $logbook->course->course_name ?? '-',
            $logbook->schedule->courseClass->class_name ?? '-',
            $logbook->room->room_name ?? '-',
            $logbook->schedule ? ($logbook->schedule->day . ' / ' . $logbook->schedule->start_time . ' - ' . $logbook->schedule->end_time) : '-',
            $logbook->login, // Already H:i:s from DB usually, but we can format if needed
            $logbook->logout ?? '-',
            $logbook->activity,
            $logbook->status ?? 'AKTIF',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
