<table class="w-full border-collapse border border-gray-300">
    <thead class="bg-gray-200">
        <tr>
            @foreach ($headers as $header)
                <th class="px-4 py-2 text-left text-gray-700 uppercase border">{{ $header }}</th>
            @endforeach
            @if (!empty($actions))
                <th class="px-4 py-2 text-left text-gray-700 uppercase border">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr class="border-t hover:bg-gray-100">
                @foreach ($headers as $key => $header)
                    <td class="px-4 py-2 border">
                        {{ is_array($row) ? ($row[$header] ?? '') : ($row->$header ?? '') }}
                    </td>
                @endforeach
                @if (!empty($actions))
                    <td class="px-4 py-2 border">
                        {!! $actions($row) !!}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
