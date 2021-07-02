@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
      'Dashboard' => backpack_url('dashboard'),
      'Navigate' => false,
      $olt->nome => false
    ];
@endphp

@section('content')
    <div class="app-body rounded bg-gray-100">
        <div class="sidebar sidebar-pills shadow-sm bg-white">
            <nav class="sidebar-nav ps" style="max-height: 47em; overflow-y: auto !important;">
                <ul class="nav">
                    <li class="nav-title">Slots</li>
                    @for($i = 1; $i <= $olt->slot; $i++)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-hdd"></i> Slot {{ $i }}</a>
                            <ul class="nav-dropdown-items">
                                @for($j = 1; $j <= $olt->pon; $j++)
                                    <li class="nav-item nav-link" onclick="getPON({{ $olt->id }}, '{{ $i }}/{{ $j }}', '1500')"><i class="nav-icon las la-microchip"></i> PON{{ $j }}</li>
                                @endfor
                            </ul>
                        </li>
                    @endfor
                </ul>
            </nav>
        </div>
        <main class="main p-2">
            <div class="row justify-content-md-end mb-2">
                <div class="input-group col-md-4">
                    <input class="form-control" type="text" placeholder="ONT Serial..." id="search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" onclick="getONU({{ $olt->id }})"><i class="las la-search"></i></button>
                    </div>
                </div>
            </div>
            <div style="height: 30em; overflow: auto;">
                <table class="bg-white table table-striped table-hover nowrap rounded shadow-sm border-xs dataTable dtr-inline collapsed has-hidden-columns overflow-hidden">
                    <thead>
                        <tr>
                            <th>Num</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Signal</th>
                            <th>Serial Number</th>
                        </tr>
                    </thead>
                    <tbody id="pon"></tbody>
                </table>
            </div>
            <div class="text-right">
                <span><b>Unauthorized ONUs</b></span>
            </div>
            <div style="height: 15em; overflow: auto;">
                <table class="bg-white table table-striped table-hover nowrap rounded shadow-sm border-xs dataTable dtr-inline collapsed has-hidden-columns overflow-hidden">
                    <thead>
                        <tr>
                            <th>PON</th>
                            <th>Serial Number</th>
                            <th class="text-right" style="font-size: 1.2em"><button class="btn btn-ghost-primary btn-pill" onclick="getPending({{ $olt->id }})"><i class="las la-sync"></i></button></th>
                        </tr>
                    </thead>
                    <tbody id="request"></tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/'.$olt->vendor.'.js') }}" defer></script>
@endsection
