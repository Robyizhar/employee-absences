<?php

namespace App\Exports;

use App\Models\AttendanceLogs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceRecapExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    // public function view(): View
    // {
        // $query = AttendanceLogs::with(['employee.department', 'machine'])
        //     ->when($this->filters['start_date'] ?? null, function ($q) {
        //         $q->whereDate('scan_time', '>=', $this->filters['start_date']);
        //     })
        //     ->when($this->filters['end_date'] ?? null, function ($q) {
        //         $q->whereDate('scan_time', '<=', $this->filters['end_date']);
        //     })
        //     ->when($this->filters['department_id'] ?? null, function ($q) {
        //         $q->whereHas('employee', function ($sub) {
        //             $sub->where('department_id', $this->filters['department_id']);
        //         });
        //     })
        //     ->when($this->filters['employee_name'] ?? null, function ($q) {
        //         $search = strtolower($this->filters['employee_name']);
        //         $q->whereHas('employee', function ($sub) use ($search) {
        //             $sub->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        //         });
        //     })
        //     ->orderBy('scan_time', 'asc');

    //     $logs = $query->get();

    //     return view('absence.export', [
    //         'logs' => $logs,
    //     ]);
    // }

    public function collection() {
        $query = AttendanceLogs::with(['employee.department', 'machine'])
            ->when($this->filters['start_date'] ?? null, function ($q) {
                $q->whereDate('scan_time', '>=', $this->filters['start_date']);
            })
            ->when($this->filters['end_date'] ?? null, function ($q) {
                $q->whereDate('scan_time', '<=', $this->filters['end_date']);
            })
            ->when($this->filters['department_id'] ?? null, function ($q) {
                $q->whereHas('employee', function ($sub) {
                    $sub->where('department_id', $this->filters['department_id']);
                });
            })
            ->when($this->filters['employee_name'] ?? null, function ($q) {
                $search = strtolower($this->filters['employee_name']);
                $q->whereHas('employee', function ($sub) use ($search) {
                    $sub->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderBy('scan_time', 'desc');

        $result = $query->get();

        // \Log::info($result);
        $tz = config('app.absence_timezone', 'Asia/Jakarta');

        return $result->map(function ($log) use($tz) {
            return [
                'Tanggal'       => $log->scan_time->format('Y-m-d'),
                'Nama Karyawan' => $log->employee->name ?? '-',
                'Departemen'    => $log->employee->department->name ?? '-',
                'Jam Scan'      => $log->scan_time->setTimezone($tz)->format('H:i:s'),
                'Status'        => $log->status,
                'Mesin'         => $log->machine->serial_number ?? '-',
            ];
        });
    }

    public function headings(): array {
        return [
            'Tanggal',
            'Nama Karyawan',
            'Departemen',
            'Jam Scan',
            'Status',
            'Mesin',
        ];
    }

    public function styles(Worksheet $sheet) {
        // Bold header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Border untuk semua cell yang berisi data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:F{$lastRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Center alignment untuk header
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
