<x-app-layout>

    <div class="py-12 h-full">
        <div class="mx-3 sm:px-6 lg:px-8">
        <div class="mx-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 md:grid md:grid-cols-7 md:gap-2">
                    <div class="col-span-1 rounded-lg shadow-md p-3">
                        <ul id="olts" class="collapsibleList cursor-pointer p-0">
                            @for($i = 1; $i <= $olt->slot; $i++)
                                <li><i class="gg-database inline-block align-middle" style="--ggs: 0.8;"></i><span class="p-2">Slot{{ $i }}</span>
                                <ul class="ml-4 collapsibleList p-0">
                                    @for($j = 1; $j <= $olt->pon; $j++)
                                        <li onclick="getPON({{ $olt->id }}, '{{ $i }}/{{ $j }}', '0')"><i class="gg-usb-c inline-block align-middle" style="--ggs: 0.8;"></i><span class="p-2">PON{{ $j }}</span></li>
                                    @endfor
                                </ul>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <div class="col-span-4 overflow-x-scroll md:overflow-x-auto">
                        <style>.gg-check:after {border-color: #84CC16;}</style>
                        <div class="rounded-lg p-2 shadow">
                            <label>Pesquisar ONU:</label>
                            <input class="rounded-lg" placeholder="Serial..." type="text" id="search">
                            <button class="rounded-lg bg-blue-500 text-white p-2" onclick="getONU({{ $olt->id }})">Pesquisar</button>
                        </div>
                        <div class="mt-2 rounded-lg" style="height: 35rem; overflow-y: scroll;">
                            <table id='onus' class='table table-striped w-full mx-auto overflow-hidden m-0'>
                                <thead>
                                <tr class='bg-gray-700 bg-gradient-to-b text-white uppercase text-sm'>
                                    <th onclick='sortNum(0)' class='py-1 px-2 cursor-pointer'>Num</th>
                                    <th class='py-1 px-2 cursor-pointer' onclick='sortStr(1)'>Status</th>
                                    <th class='py-1 px-2 cursor-pointer' onclick='sortStr(2)'>Descrição</th>
                                    <th class='py-1 px-2 cursor-pointer' onclick='sortNum(3)'>Sinal</th>
                                    <th onclick='sortStr(4)' class='py-1 px-2 cursor-pointer'>Serial</th>
                                </tr>
                                </thead>
                                <tbody id="pon">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-span-2 border-l-2"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
