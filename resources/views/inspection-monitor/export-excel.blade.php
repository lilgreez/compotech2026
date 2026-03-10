<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline;">ZC Diecast - Dieset Inspection</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Dieset</th>
            <th>Tanggal</th>
            <th>Parts</th>
            <th>Shoot</th>
            <th>Gambar Parts</th>
            <th>Jenis Kerusakan</th>
            <th>Tindakan Perbaikan</th>
            <th>Detil Inspeksi</th>
            <th>Gambar Inspeksi</th>
            <th>Mekanik</th>
        </tr>
    </thead>
    <tbody>
        @foreach($diesets as $dieset)
            @foreach($dieset->parts as $part)
                @foreach($part->inspectionHistories as $history)
                    <tr>
                        <td>{{ $dieset->name }}</td>
                        <td>{{ $history->inspection_date->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $part->category ?? '' }} - ({{ $part->part_code }}) {{ $part->name }}; CAVITY: {{ $part->cavity_number }}</td>
                        <td>{{ $history->parts_shoot }}</td>
                        <!-- Untuk Gambar Excel, karena library default export View tidak otomatis render image inline Excel, kita kirim Link URL-nya -->
                        <td>{{ $part->image_path ? url('storage/'.$part->image_path) : '' }}</td>
                        <td>{{ $history->condition }}</td>
                        <td>{{ $history->action_taken }}</td>
                        <td>Alasan: {{ $history->reason }}. Detail: {{ $history->damage_details }}</td>
                        <td>{{ $history->evidence_photo_path ? url('storage/'.$history->evidence_photo_path) : '' }}</td>
                        <td>{{ $history->operator->name ?? '' }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>