<x-app-layout>
    <div class="py-12 min-h-full">
        <div class="mx-3 sm:px-6 lg:px-8">
        <div class="mx-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 items-center">
                    <table class="table-auto striped hadow-md mx-auto rounded-lg overflow-hidden shadow-md">
                        <thead class="w-full">
                            <tr class="bg-gray-400">
                                <th class="p-2">Nome</th>
                                <th class="p-2">IP</th>
                                <th class="p-2">Fabricante</th>
                                <th class="p-2">Firmware</th>
                                <th class="p-2">CPU</th>
                                <th class="p-2">Memória</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($olts as $olt)
                                <tr class="border-b">
                                    <td class="p-2"><a href="/navigate?olt={{ $olt->id }}">{{ $olt->nome }}</a></td>
                                    <td class="p-2">{{ $olt->ip }}</td>
                                    <td class="p-2">{{ ucfirst($olt->vendor) }}</td>
                                    <td class="p-2">{{ $olt->firmware }} <a href="/firmware?id={{ $olt->id }}"><i class="gg-sync inline-block align-middle text-green-600" style="--ggs: 0.8;"></i></a></td>
                                    <td class="p-2 text-center">{{ $olt->last_cpu }}% <a href="/cpu?id={{ $olt->id }}"><i class="gg-sync inline-block align-middle text-green-600" style="--ggs: 0.8;"></i></a></td>
                                    <td class="p-2 text-center">{{ $olt->last_mem }}% <a href="/mem?id={{ $olt->id }}"><i class="gg-sync inline-block align-middle text-green-600" style="--ggs: 0.8;"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
