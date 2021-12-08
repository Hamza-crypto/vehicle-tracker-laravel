@php
    $role = Auth()->user()->role;
@endphp
<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('dashboard.index') }}">
            <span class="align-middle me-3">{{ env("APP_NAME") }}</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                General
            </li>
            <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Vehicles
            </li>
            <li class="sidebar-item {{ request()->is('vehicles') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('vehicles.index') }}">
                    <i class="align-middle" data-feather="truck"></i>
                    <span class="align-middle">All Vehicles</span>
                </a>
            </li>
            @if( $role != 'user')

                <li class="sidebar-item {{ request()->is('vehicles/upload*') ? 'active' : '' }} ">
                    <a data-target="#upload" data-toggle="collapse" class="sidebar-link {{ request()->is('users/*') ? 'collapsed' : '' }}">
                        <i class="align-middle" data-feather="plus-square"></i>
                        <span class="align-middle">Upload Files</span>
                    </a>
                    <ul id="upload"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('vehicles/upload*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('vehicles/upload/buy') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('upload.create.buy') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Buy</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('vehicles/upload/inventory') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('upload.create.inventory') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Inventory</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('vehicles/upload/sold') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('upload.create.sold') }}">
                                <i class="align-middle" data-feather="plus-square"></i>
                                <span class="align-middle">Sold</span>
                            </a>
                        </li>



                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('vehicles/create') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('vehicles.create') }}">
                        <i class="align-middle" data-feather="plus-square"></i>
                        <span class="align-middle">Add New Vehicle</span>
                    </a>
                </li>
            @endif


            @if( $role == 'admin')
                <li class="sidebar-header">
                    Manage
                </li>
                <li class="sidebar-item {{ request()->is('users*') ? 'active' : '' }} ">
                    <a data-target="#users" data-toggle="collapse" class="sidebar-link {{ request()->is('users/*') ? 'collapsed' : '' }}">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Users</span>
                    </a>
                    <ul id="users"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('users*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('users.index') }}">
                                <i class="align-middle" data-feather="users"></i>
                                <span class="align-middle">All Users</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('users/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('users.create') }}">
                                <i class="align-middle" data-feather="user-plus"></i>
                                <span class="align-middle">Add New User</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if( $role != 'user')
                <li class="sidebar-item {{ request()->is('locations*') ? 'active' : '' }} ">
                    <a data-target="#locations" data-toggle="collapse" class="sidebar-link {{ request()->is('users/*') ? 'collapsed' : '' }}">
                        <i class="align-middle" data-feather="map-pin"></i>
                        <span class="align-middle">Locations</span>
                    </a>
                    <ul id="locations"
                        class="sidebar-dropdown list-unstyled collapse {{ request()->is('locations*') ? 'show' : '' }}"
                        data-parent="#sidebar">

                        <li class="sidebar-item {{ request()->is('locations') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('locations.index') }}">
                                <i class="align-middle" data-feather="map-pin"></i>
                                <span class="align-middle">All Location</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('locations/create') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('locations.create') }}">
                                <i class="align-middle" data-feather="plus"></i>
                                <span class="align-middle">Add New Location</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</nav>
