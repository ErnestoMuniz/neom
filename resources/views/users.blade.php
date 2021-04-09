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
                                        <th class='py-1 px-2 cursor-pointer rounded-tl-lg'>Usuário</th>
                                        <th class='py-1 px-2 cursor-pointer'>Email</th>
                                        <th class='py-1 px-2 cursor-pointer'>Grupo</th>
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
                                    @foreach($users as $user)
                                        {{-- Gerar a tabela --}}
                                        <tr class="text-center border-b">
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            @if($user->role == 1)
                                                <td>Admin</td>
                                            @elseif($user->role == 2)
                                                <td>N2</td>
                                            @else
                                                <td>N1</td>
                                            @endif

                                            <td class="text-center">
                                                <i onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->role }})" class="gg-edit-markup inline-block align-middle text-blue-500 cursor-pointer" style="--ggs: 0.8;"></i>
                                                <a href="/removeUser?id={{ $user->id }}"><i class="gg-remove inline-block align-middle text-red-600 cursor-pointer" style="--ggs: 0.8;"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-span-1 rounded-lg shadow-md p-3">
                            <form class="text-center" action="{{ route('editUser') }}" method="post">
                                @csrf
                                <h2 class="text-xl m-1 font-bold">Editar Usuário</h2>
                                <input type="hidden" name="id" id="id" value="">
                                <div class="block mb-2">
                                    <label>Nome:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="Seu Nome" type="text" name="name" id="name" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Email:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="seu@email.com" type="email" name="email" id="email" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Senha:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="Sua Senha (OPCIONAL)" type="password" name="password" id="senha">
                                </div>
                                <div class="block mb-2">
                                    <label>Grupo:</label>
                                    <select class="rounded-lg border-gray-200" name="group" id="group">
                                        <option value="admin">Admin</option>
                                        <option value="n2">N2</option>
                                        <option value="n1">N1</option>
                                    </select>
                                </div>
                                <input class="rounded-lg cursor-pointer bg-blue-400 px-2 py-1 text-white" type="submit" value="Editar">
                            </form>
                        </div>
                        <!-- Criar novo Usuário -->
                        <div class="col-span-1 rounded-lg shadow-md p-3">
                            <form class="text-center" action="{{ route('newUser') }}" method="post">
                                @csrf
                                <h2 class="text-xl m-1 font-bold">Novo Usuário</h2>
                                <div class="block mb-2">
                                    <label>Nome:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="Seu Nome" type="text" name="name" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Email:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="seu@email.com" type="email" name="email" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Senha:</label>
                                    <input class="rounded-lg border-gray-200" placeholder="Sua Senha" type="password" name="password" id="" required>
                                </div>
                                <div class="block mb-2">
                                    <label>Grupo:</label>
                                    <select class="rounded-lg border-gray-200" name="group">
                                        <option value="admin">Admin</option>
                                        <option value="n2">N2</option>
                                        <option value="n1">N1</option>
                                    </select>
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
