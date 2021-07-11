@extends(backpack_view('blank'))

@php
    Widget::add()->to('before_content')
        ->type('div')
        ->class('row col-md-12 ml-1')
        ->content([
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-info mb-2 text-center',
                'value'       => \App\Models\Olt::count() . ' <i class="la la-server"></i>',
                'description' => 'OLTs.'
            ],
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-info mb-2 text-center',
                'value'       => \App\Models\User::count() . ' <i class="la la-user"></i>',
                'description' => 'Users'
            ]
    ]);
@endphp

@section('content')
    <table class="bg-white table table-striped table-hover nowrap rounded shadow-sm border-xs mt-2 dataTable dtr-inline collapsed has-hidden-columns overflow-hidden">
        <thead class="w-full">
        <tr class="bg-gray-700 text-white">
            <th class="p-2">Name</th>
            <th class="p-2">IP</th>
            <th class="p-2">Vendor</th>
            <th class="p-2">Firmware</th>
            <th class="p-2">CPU</th>
            <th class="p-2">Memory</th>
        </tr>
        </thead>
        <tbody>
        @foreach($olts as $olt)
            <tr class="border-b">
                <td class="p-2"><a href="/navigate?olt={{ $olt->id }}">{{ $olt->nome }}</a></td>
                <td class="p-2">{{ $olt->ip }}</td>
                <td class="p-2">{{ ucfirst($olt->vendor) }}</td>
                <td class="p-2">{{ $olt->firmware }} <a href="/get/{{ $olt->vendor }}/firmware?id={{ $olt->id }}"><i class="las la-sync"></i></a></td>
                <td class="p-2 text-center">{{ $olt->last_cpu }}% <a href="/get/{{ $olt->vendor }}/cpu?id={{ $olt->id }}"><i class="las la-sync"></i></a></td>
                <td class="p-2 text-center">{{ $olt->last_mem }}% <a href="/get/{{ $olt->vendor }}/mem?id={{ $olt->id }}"><i class="las la-sync"></i></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
