@props(['headers' => []])

<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">

        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            {{ $slot }}
        </tbody>

    </table>
</div>