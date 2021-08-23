@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
      'Dashboard' => backpack_url('dashboard'),
      'Navigate' => false,
      $olt->nome => false
    ];
@endphp

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <div class="app-body rounded bg-gray-100">
        <div class="sidebar sidebar-pills shadow-sm bg-white">
            <nav class="sidebar-nav ps" style="max-height: 47em; overflow-y: auto !important;">
                <ul class="nav">
                    <li class="nav-title">Slots</li>
                    @for($i = 1; $i <= $olt->slot; $i++)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-hdd"></i> Slot {{ $i }}</a>
                            <ul class="nav-dropdown-items">
                                @if ($olt->vendor != 'huawei')
                                    @for($j = 1; $j <= $olt->pon; $j++)
                                        <li class="nav-item nav-link" onclick="getPON({{ $olt->id }}, '{{ $i }}/{{ $j }}', '1500')"><i class="nav-icon las la-microchip"></i> PON{{ $j }}</li>
                                    @endfor
                                @else
                                    @for($j = 0; $j <= $olt->pon; $j++)
                                        <li class="nav-item nav-link" onclick="getPON({{ $olt->id }}, '{{ $i }}/{{ $j }}', '1500')"><i class="nav-icon las la-microchip"></i> PON{{ $j }}</li>
                                    @endfor
                                @endif

                            </ul>
                        </li>
                    @endfor
                </ul>
            </nav>
        </div>
        <main class="main p-2">
            <div class="row mb-2">
                <span class="my-auto col-md-4 pl-4" id="pon-index"><b> — ONU List</b></span>
                <div class="input-group col-md-4 offset-md-4">
                    <button class="btn btn-primary mr-2" onclick="getPON()" id="btn-refresh"><i class="las la-redo-alt"></i></button>
                    <input class="form-control" type="text" placeholder="ONT Serial..." id="search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" onclick="getONU({{ $olt->id }})" id="btn-search"><i class="las la-search"></i></button>
                    </div>
                </div>
                <script type="text/javascript">
                var input = document.getElementById("search");

                // Execute a function when the user releases a key on the keyboard
                input.addEventListener("keyup", function(event) {
                    // Number 13 is the "Enter" key on the keyboard
                    if (event.keyCode === 13) {
                        // Cancel the default action, if needed
                        event.preventDefault();
                        // Trigger the button element with a click
                        document.getElementById("btn-search").click();
                    }
                });
                </script>
            </div>
            <div style="height: 30em; overflow: auto;">
                <style>
                    @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
                </style>
                <table class="bg-white table table-hover nowrap rounded shadow-sm border-xs dataTable dtr-inline collapsed has-hidden-columns overflow-hidden">
                    <thead>
                        <tr>
                            <th>Num</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Signal</th>
                            <th>Serial Number</th>
                            <th>Scripts</th>
                        </tr>
                    </thead>
                    <tbody id="pon"></tbody>
                </table>
            </div>
            <div class="pt-2">
                <span class="pl-2"><b> — Unauthorized ONUs</b></span>
            </div>
            <div style="height: 15em; overflow: auto;">
                <table class="bg-white table table-striped table-hover nowrap rounded shadow-sm border-xs dataTable dtr-inline collapsed has-hidden-columns overflow-hidden">
                    <thead>
                        <tr>
                            <th>PON</th>
                            <th>Serial Number</th>
                            <th class="text-right" style="font-size: 1.2em"><button class="btn btn-ghost-primary btn-pill" onclick="getPending({{ $olt->id }})"><i class="las la-sync" id="refresh-pending"></i></button></th>
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
