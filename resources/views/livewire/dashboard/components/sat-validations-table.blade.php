<div>
    <div class="relative overflow-x-auto border border-neutral-200 dark:border-neutral-700 rounded-lg">
        <table class="w-auto text-sm text-neutral-700 dark:text-neutral-400">
            <thead>
                <tr class="text-xs text-neutral-500 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <th class="py-3 px-2 text-nowrap">UUID</th>
                    <th class="py-3 px-2 text-nowrap">CFDI Status</th>
                    <th class="py-3 px-2 text-nowrap">Issuer RFC</th>
                    <th class="py-3 px-2 text-nowrap">Receiver RFC</th>
                    <th class="py-3 px-2 text-right">Total</th>
                    <th class="py-3 px-2 text-nowrap">Response Code</th>
                    <th class="py-3 px-2 text-nowrap">Cancellable Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($satValidations as $satValidation)
                    @php
                        $rowClass = $loop->even ? 'bg-neutral-50 dark:bg-neutral-800' : 'bg-white dark:bg-neutral-900';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="py-2 px-2 text-nowrap">{{ $satValidation->uuid }}</td>
                        <td class="py-2 px-2 text-nowrap font-semibold">{{ $satValidation->cfdi_status }}</td>
                        <td class="py-2 px-2 text-nowrap">{{ $satValidation->issuer_rfc }}</td>
                        <td class="py-2 px-2 text-nowrap">{{ $satValidation->receiver_rfc }}</td>
                        <td class="py-2 px-2 text-right">{{ number_format($satValidation->total, 2) }}</td>
                        <td class="py-2 px-2 text-nowrap">{{ $satValidation->response_code }}</td>
                        <td class="py-2 px-2 text-nowrap">{{ $satValidation->cancellable_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $satValidations->links() }}
    </div>
</div>
