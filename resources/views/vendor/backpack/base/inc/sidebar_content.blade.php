<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@canany(['manage_users', 'manage_olts'])
    <li class="nav-title">Administration</li>
@endcan
@can('manage_users')
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
        </ul>
    </li>
@endcan
@can('manage_olts')
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('olt') }}'><i class="nav-icon las la-server"></i> OLTs</a></li>
@endcan
@can('manage_interactions')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('interaction') }}'><i class='nav-icon la la-project-diagram'></i> Interactions</a></li>
@endcan

<li class="nav-title text-center" style="position: absolute; bottom: 0.5em;">Made by <a href="https://github.com/ErnestoMuniz">Ernesto Muniz</a> Version Alpha 2.10.0</li>
