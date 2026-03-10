<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline;">Less Stock Dieset Parts</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Parts Code</th>
            <th>Parts Name</th>
            <th>Parts Desc</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach($parts as $part)
            <tr>
                <td>{{ $part->part_code ?? '-' }}</td>
                <td>{{ $part->name }}</td>
                <td>{{ $part->description ?? '-' }}</td>
                <td>{{ $part->current_stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>