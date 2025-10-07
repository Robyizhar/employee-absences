<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Karyawan</th>
            <th>Departemen</th>
            <th>Jam Scan</th>
            <th>Status</th>
            <th>Mesin</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $i => $log)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $log->scan_time->format('Y-m-d') }}</td>
                <td>{{ $log->employee->name ?? '-' }}</td>
                <td>{{ $log->employee->department->name ?? '-' }}</td>
                <td>{{ $log->scan_time->format('H:i:s') }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->machine->serial_number ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
