<x-app-layout>

    <div class="py-12 min-h-full">
        <div class="mx-3 sm:px-6 lg:px-8">
        <div class="mx-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-6 gap-2">
                    <div class="col-span-1 border-r-2">
                        <ul id="olts" class="collapsibleList cursor-pointer">
                            @foreach($olts as $olt)
                                <li class="mb-2"><i class="gg-server inline-block align-middle"></i><span class="p-2">{{ $olt->nome }}</span>
                                    <ul class="collapsibleList ml-4">
                                        @for($i = 1; $i <= $olt->slot; $i++)
                                            <li><i class="gg-database inline-block align-middle" style="--ggs: 0.8;"></i><span class="p-2">Slot{{ $i }}</span>
                                            <ul class="ml-4 collapsibleList">
                                                @for($j = 1; $j <= $olt->pon; $j++)
                                                    <li onclick="getPON({{ $olt->id }}, '{{ $i }}/{{ $j }}')"><i class="gg-usb-c inline-block align-middle" style="--ggs: 0.8;"></i><span class="p-2">PON{{ $j }}</span></li>
                                                @endfor
                                            </ul>
                                            </li>
                                        @endfor
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-span-3" id="pon"></div>
                    <div class="col-span-2 border-l-2"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
