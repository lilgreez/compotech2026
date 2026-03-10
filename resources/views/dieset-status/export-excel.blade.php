<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 14px; font-weight: bold;">Stock Dieset Parts</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Dieset</th>
            <th>Kategori</th>
            <th>Cavity</th>
            <th>Kode Parts</th>
            <th>Nama Parts</th>
            <th>Desc Parts</th>
            <th>Actual Shoot</th>
            <th>Max Shoot</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach($diesets as $dieset)
            @foreach($dieset->parts as $index => $part)
                <tr>
                    <td>{{ $index === 0 ? $dieset->name : '' }}</td>
                    <td>{{ $part->category ?? '-' }}</td>
                    <td>{{ $part->cavity_number ?? '-' }}</td>
                    <td>{{ $part->part_code ?? '-' }}</td>
                    <td>{{ $part->name }}</td>
                    <td>{{ $part->description ?? '-' }}</td>
                    <td>{{ $part->actual_shoot }}</td>
                    <td>{{ $part->max_shoot }}</td>
                    <td>{{ $part->current_stock }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>