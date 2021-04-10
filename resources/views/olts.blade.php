<x-app-layout>
    <div class="py-12 min-h-full">
        <div class="mx-3 sm:px-6 lg:px-8">
            <div class="mx-3 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-4 gap-2">
                        <div class="col-span-2">
                            <table class="table-auto w-full shadow-md">
                                <thead>
                                    <tr class="bg-gray-300">
                                        <th class='py-1 px-2 cursor-pointer rounded-tl-lg'>Nome</th>
                                        <th class='py-1 px-2 cursor-pointer'>IP</th>
                                        <th class='py-1 px-2 cursor-pointer'>User</th>
                                        <th class='py-1 px-2 cursor-pointer'>Slots</th>
                                        <th class='py-1 px-2 cursor-pointer'>PONs</th>
                                        <th class='py-1 px-2 cursor-pointer rounded-tr-lg'>Ações</th>
                                    </tr>
                                </thead>
                                <!-- Estilizar a tabela -->
                                <style>
                                    tr:nth-child(even){
                                        background-color: #f3f4f6;
                                    }
                                    tr:last-child td:first-child{
                                        border-bottom-left-radius: 0.5rem;
                                    }
                                    tr:last-child td:last-child{
                                        border-bottom-right-radius: 0.5rem;
                                    }
                                    tr:last-child {
                                        border-bottom: 0;
                                    }
                                </style>
                                <tbody>
                                    @foreach($olts as $olt)
                                        {{-- Gerar a tabela --}}
                                        <tr class="text-center border-b">
                                            <td>{{ $olt->nome }}</td>
                                            <td>{{ $olt->ip }}</td>
                                            <td>{{ $olt->user }}</td>
                                            <td>{{ $olt->slot }}</td>
                                            <td>{{ $olt->pon }}</td>
                                            <td class="text-center">
                                                <i onclick="editOlt({{ $olt->id }}, '{{ $olt->nome }}', '{{ $olt->ip }}', '{{ $olt->user }}', {{ $olt->slot }}, '{{ $olt->pon }}')" class="gg-edit-markup inline-block align-middle text-blue-500 cursor-pointer" style="--ggs: 0.8;"></i>
                                                <a href="/removeOlt?id={{ $olt->id }}"><i class="gg-remove inline-block align-middle text-red-600 cursor-pointer" style="--ggs: 0.8;"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-span-1 rounded-lg shadow-md p-3">
                            <form class="text-center" action="{{ route('editOlt') }}" method="post">
                                @csrf
                                <h2 class="text-xl m-1 font-bold">Editar OLT</h2>
                                <input type="hidden" name="id" value="" id="id">
                                <div class="block mb-2">
                                    <label>Nome:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="nome" id="nome" required>
                                </div>
                                <div class="block mb-2">
                                    <label>IP:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="ip" id="ip" required>
                                </div>
                                <div class="block mb-2">
                                    <label>User:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="user" id="user" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Senha:</label>
                                    <input class="rounded-lg border-gray-200" type="password" name="pass">
                                </div>
                                <div class="block mb-2">
                                    <label>Slots:</label>
                                    <input class="rounded-lg border-gray-200" type="number" name="slots" id="slot" required>
                                </div>
                                <div class="block mb-2">
                                    <label>PONs:</label>
                                    <input class="rounded-lg border-gray-200" type="number" name="pons" id="pon" required>
                                </div>
                                <input class="rounded-lg cursor-pointer bg-blue-400 px-2 py-1 text-white" type="submit" value="Editar">
                            </form>
                        </div>
                        <!-- Criar nova OLT -->
                        <div class="col-span-1 rounded-lg shadow-md p-3">
                            <form class="text-center" action="{{ route('newOlt') }}" method="post">
                                @csrf
                                <h2 class="text-xl m-1 font-bold">Nova OLT</h2>
                                <div class="block mb-2">
                                    <label>Nome:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="nome" required>
                                </div>
                                <div class="block mb-2">
                                    <label>IP:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="ip" required>
                                </div>
                                <div class="block mb-2">
                                    <label>User:</label>
                                    <input class="rounded-lg border-gray-200" type="text" name="user" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Senha:</label>
                                    <input class="rounded-lg border-gray-200" type="password" name="pass" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Slots:</label>
                                    <input class="rounded-lg border-gray-200" type="number" name="slots" required>
                                </div>
                                <div class="block mb-2">
                                    <label>PONs:</label>
                                    <input class="rounded-lg border-gray-200" type="number" name="pons" required>
                                </div>
                                <input class="rounded-lg cursor-pointer bg-blue-400 px-2 py-1 text-white" type="submit" value="Cadastrar">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
