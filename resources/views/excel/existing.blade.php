<table>
    <thead>
        <tr>
            <td>Category</td>
            <td>DAU</td>
            <td>NRU</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($dau as $item)
            <tr>
                <td>$dau->date</td>
                <td>$dau->total</td>
            </tr>
        @endforeach
    </tbody>
</table>