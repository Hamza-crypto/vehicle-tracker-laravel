<div class="row">

    @php
        $role = Auth()->user()->role;
    @endphp


    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card illustration flex-fill">
            <div class="card-body p-0 d-flex flex-fill">
                <div class="row g-0 w-100">
                    <div class="col-6">
                        <div class="illustration-text p-3 m-1">
                            <h4 class="illustration-text">Welcome Back, {{ Auth()->user()->name }}</h4>
                            <p class="mb-0">{{ env('APP_NAME') }} Dashboard</p>
                        </div>
                    </div>
                    <div class="col-6 align-self-end text-end">
                        <img src="{{ asset('assets/img/customer-support.png') }}" alt="Customer Support"
                            class="img-fluid illustration-img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($role == 'admin')
        <div class="col-12 col-sm-6 col-xxl d-flex">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-2"> {{ \App\Models\User::count() }} Users </h3>
                            <p class="mb-2">Total Users</p>
                            <div class="mb-0">
                                <span class="badge badge-soft-success mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'admin')->count() }} Admin(s)
                                </span>

                                <span class="badge badge-soft-primary mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'vehicle_manager')->count() }} Vehicle Manager
                                </span>

                                <span class="badge badge-soft-info mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'yard_manager')->count() }} Yard Manager
                                </span>

                                <span class="badge badge-soft-warning mr-2">
                                    <i class="mdi mdi-arrow-bottom-right"></i>
                                    {{ \App\Models\User::where('role', 'viewer')->count() }} Viewer
                                </span>


                            </div>
                        </div>
                        <div class="d-inline-block ml-3">
                            <div class="stat">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-users align-middle text-success">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countAllVehicles() }} Vehicles</h3>
                        <p class="mb-2"><a href="{{ route('vehicles.index') }}" target="_blank"> Total Vehicles</a>
                        </p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M766.976 508.736c80.576 0 152.448 32.128 199.232 82.176" fill="#AEBCC3" />
                                <path
                                    d="M64.704 684.992c10.816 19.2 32.064 32.192 56.576 32.192h784.64c35.84 0 64.832-27.648 64.832-61.76v-17.408h-36.608a15.744 15.744 0 0 1-16.064-15.296V550.912a277.568 277.568 0 0 0-150.144-44.16h1.6l-55.04-0.256c-53.632-115.2-157.504-210.752-294.208-210.752-136.512 0-251.008 89.728-282.176 210.688h-16.832c-35.456 0-56.128 27.392-56.128 61.184"
                                    fill="#E8447A" />
                                <path
                                    d="M64.704 654.464h13.76a39.168 39.168 0 0 0 40.064-38.272v-17.6c0-21.12-17.92-38.208-40.064-38.208h-13.376"
                                    fill="#F5BB1D" />
                                <path d="M160 684.992a101.632 96.832 0 1 0 203.264 0 101.632 96.832 0 1 0-203.264 0Z"
                                    fill="#455963" />
                                <path d="M218.88 684.992a42.752 40.768 0 1 0 85.504 0 42.752 40.768 0 1 0-85.504 0Z"
                                    fill="#AEBCC3" />
                                <path
                                    d="M652.032 684.992a101.568 96.832 0 1 0 203.136 0 101.568 96.832 0 1 0-203.136 0Z"
                                    fill="#455963" />
                                <path d="M710.912 684.992a42.752 40.768 0 1 0 85.504 0 42.752 40.768 0 1 0-85.504 0Z"
                                    fill="#AEBCC3" />
                                <path
                                    d="M966.272 591.104v-0.192a257.92 257.92 0 0 0-48.192-40V622.72c0 8.448 7.232 15.296 16.064 15.296h36.608v-42.304l-4.48-4.608z"
                                    fill="#F5BB1D" />
                                <path
                                    d="M405.568 335.616c-104.896 6.336-191.296 76.8-216.64 170.816h216.64V335.616zM445.696 506.432h216.64c-41.216-86.848-117.12-159.616-216.64-170.048v170.048z"
                                    fill="#631536" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countVehicles('READY FOR AUCTION') }} Ready For
                            Auction</h3>
                        <p class="mb-2"><a href="{{ route('vehicles.index', ['status' => 'READY FOR AUCTION']) }}"
                                target="_blank">Ready For Auction</a>
                        </p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                height="800px" width="800px" version="1.1" id="Layer_1" viewBox="0 0 509 509"
                                xml:space="preserve">
                                <circle style="fill:#4B5AA7;" cx="254.5" cy="254.5" r="254.5" />
                                <g>
                                    <path style="fill:#D7E2F2;"
                                        d="M72.3,172.1v-10.6c0-4.1-3.3-7.4-7.4-7.4h-29c-8.8,0-15.9,7.1-15.9,15.9s7.1,15.9,15.9,15.9h46.7   V172H72.3V172.1z" />
                                    <path style="fill:#D7E2F2;"
                                        d="M436.7,172.1v-10.6c0-4.1,3.3-7.4,7.4-7.4h29c8.8,0,15.9,7.1,15.9,15.9s-7.1,15.9-15.9,15.9h-46.7   V172h10.3V172.1z" />
                                </g>
                                <g>
                                    <path style="fill:#333842;"
                                        d="M38.1,318.9v97.9c0,4.9,3.9,8.8,8.8,8.8h40.5c4.9,0,8.8-3.9,8.8-8.8v-97.9H38.1z" />
                                    <path style="fill:#333842;"
                                        d="M470.9,318.9v97.9c0,4.9-3.9,8.8-8.8,8.8h-40.5c-4.9,0-8.8-3.9-8.8-8.8v-97.9H470.9z" />
                                </g>
                                <path style="fill:#BECEE7;"
                                    d="M458,196.5l-17.8-8.9c0,0-36.5-77.9-50.3-89.8c-6.9-5.9-71.1-14.5-135.4-14.5  c-64.2,0-128.5,8.6-135.4,14.5c-13.8,11.8-50.3,89.8-50.3,89.8L51,196.5L30.3,236l12.3,132.2c0.8,9,8.4,15.8,17.3,15.8h389  c9,0,16.5-6.8,17.3-15.8L478.5,236L458,196.5z" />
                                <polygon style="fill:#8FA3C1;" points="56.4,232.5 38.1,273.9 121,278.5 142.4,245.2 " />
                                <polygon style="fill:#F8B517;" points="56.4,232.5 56.4,274.9 38.1,273.9 " />
                                <g>
                                    <circle style="fill:#FFFFFC;" cx="76.2" cy="255.1" r="16.8" />
                                    <circle style="fill:#FFFFFC;" cx="109.7" cy="259.8" r="13.5" />
                                </g>
                                <path style="fill:#393D47;"
                                    d="M125.2,335.7l-15.5,29.1H59.3l7.7-18.6c2.6-6.4,8.9-10.5,15.7-10.5H125.2z" />
                                <circle style="fill:#DEDEDF;" cx="84.6" cy="350.7" r="11.6" />
                                <g>
                                    <path style="fill:#D7E2F2;"
                                        d="M98.6,187.7c0,0,40.3,8.9,57.9,58.6l-19.6,42.2l-13.7-10l19.2-33.2   C142.4,245.2,135.9,216.4,98.6,187.7z" />
                                    <path style="fill:#D7E2F2;"
                                        d="M136.9,288.4c0,0-92.5,6.8-98.8,0c-6.4-6.8,0-14.5,0-14.5l85.1,4.6L136.9,288.4z" />
                                </g>
                                <polygon style="fill:#8FA3C1;"
                                    points="452.6,232.5 470.9,273.9 388,278.5 366.6,245.2 " />
                                <polygon style="fill:#F8B517;" points="452.6,232.5 452.6,274.9 470.9,273.9 " />
                                <g>
                                    <circle style="fill:#FFFFFC;" cx="432.8" cy="255.1" r="16.8" />
                                    <circle style="fill:#FFFFFC;" cx="399.3" cy="259.8" r="13.5" />
                                </g>
                                <path style="fill:#393D47;"
                                    d="M383.8,335.7l15.5,29.1h50.3l-7.7-18.6c-2.6-6.4-8.9-10.5-15.7-10.5H383.8z" />
                                <circle style="fill:#DEDEDF;" cx="424.4" cy="350.7" r="11.6" />
                                <path style="fill:#D7E2F2;"
                                    d="M410.4,187.7c0,0-40.3,8.9-57.9,58.6l19.6,42.2l13.7-10l-19.2-33.2  C366.6,245.2,373.1,216.4,410.4,187.7z" />
                                <g>
                                    <path style="fill:#393D47;"
                                        d="M372.6,292.9c-5.5-15.2-19.1-44.5-26.7-46.7c-3.8-1.1-47.6-3.5-91.4-3.5s-87.6,2.5-91.4,3.5   c-7.6,2.2-21.2,31.5-26.7,46.7c-5.5,15.2,8.1,15,8.1,15h220.1C364.6,308,378.1,308.1,372.6,292.9z" />
                                    <path style="fill:#393D47;"
                                        d="M352.5,342.3h-196c0,0-15.6,0.3-27.2,22.6h250.3C368.1,342.6,352.5,342.3,352.5,342.3z" />
                                    <polygon style="fill:#393D47;"
                                        points="373.9,373.4 135.1,373.4 130.4,383.9 378.6,383.9  " />
                                </g>
                                <path style="fill:#4485C5;"
                                    d="M383.8,105.4c-11.1-3.6-70.2-10-129.3-10s-118.2,6.4-129.3,10c0,0-31.8,45-45.7,82.3h350  C415.6,150.4,383.8,105.4,383.8,105.4z" />
                                <path style="fill:#D7E2F2;"
                                    d="M372.1,288.4c0,0,92.5,6.8,98.8,0c6.4-6.8,0-14.5,0-14.5l-85.1,4.6L372.1,288.4z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countVehicles('WAITING FOR ORIGINAL TITLE') }}
                            Waiting For Original Title </h3>
                        <p class="mb-2"><a
                                href="{{ route('vehicles.index', ['status' => 'WAITING FOR ORIGINAL TITLE']) }}"
                                target="_blank">Waiting for original title</a></p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px"
                                viewBox="0 0 1024 1024" class="icon" version="1.1">
                                <path
                                    d="M200.8 353.9c-8 0-14.5-6.5-14.5-14.5v-60.9c0-8 6.5-14.5 14.5-14.5s14.5 6.5 14.5 14.5v60.9c0 8-6.5 14.5-14.5 14.5z"
                                    fill="#A4A9AD" />
                                <path d="M200.8 263.9c-8 0-14.5 6.5-14.5 14.5v25.5h29v-25.5c0-8-6.5-14.5-14.5-14.5z"
                                    fill="" />
                                <path
                                    d="M597.5 353.9c-8 0-14.5-6.5-14.5-14.5v-60.9c0-8 6.5-14.5 14.5-14.5s14.5 6.5 14.5 14.5v60.9c0 8-6.4 14.5-14.5 14.5z"
                                    fill="#A4A9AD" />
                                <path d="M597.5 263.9c-8 0-14.5 6.5-14.5 14.5v25.5h29v-25.5c0-8-6.4-14.5-14.5-14.5z"
                                    fill="" />
                                <path
                                    d="M635.3 287.2H163c-8 0-14.5-6.5-14.5-14.5s6.5-14.5 14.5-14.5h472.3c8 0 14.5 6.5 14.5 14.5s-6.5 14.5-14.5 14.5z"
                                    fill="#A4A9AD" />
                                <path d="M839.9 390h29v91.6h-29z" fill="#333E48" />
                                <path d="M840 390v29.2c4.6 1.6 9.4 2.4 14.5 2.4s10-0.9 14.5-2.4V390h-29z"
                                    fill="" />
                                <path d="M854.5 377.1m-29.7 0a29.7 29.7 0 1 0 59.4 0 29.7 29.7 0 1 0-59.4 0Z"
                                    fill="#A4A9AD" />
                                <path
                                    d="M901.7 478H693.1l-20.3-119.7C669.7 340 652 325 633.4 325H179c-18.6 0-36.3 15-39.4 33.3L92.8 634.2c-3.1 18.3 9.5 33.3 28.1 33.3h780.8c18.6 0 33.8-15.2 33.8-33.8v-122c0-18.5-15.2-33.7-33.8-33.7z"
                                    fill="#0071CE" />
                                <path d="M866.2 565.3h69.3v47h-69.3z" fill="#FFB819" />
                                <path d="M877.2 612.3h-15.9c-3.7 0-6.8-3-6.8-6.7V572c0-3.7 3-6.7 6.8-6.7h15.9v47z"
                                    fill="#FFFFFF" />
                                <path d="M104.5 565.3l-8 47h60.3v-47z" fill="#FFB819" />
                                <path d="M145.9 612.3h15.9c3.7 0 6.7-3 6.7-6.7V572c0-3.7-3-6.7-6.7-6.7h-15.9v47z"
                                    fill="#FFFFFF" />
                                <path d="M403.6 667.5c0-65.6-53.2-118.8-118.8-118.8S166 601.9 166 667.5h237.6z"
                                    fill="" />
                                <path d="M284.8 667.5m-97.5 0a97.5 97.5 0 1 0 195 0 97.5 97.5 0 1 0-195 0Z"
                                    fill="#333E48" />
                                <path d="M284.8 667.5m-49.4 0a49.4 49.4 0 1 0 98.8 0 49.4 49.4 0 1 0-98.8 0Z"
                                    fill="#A4A9AD" />
                                <path d="M832.6 667.5c0-65.6-53.2-118.8-118.8-118.8S595 601.9 595 667.5h237.6z"
                                    fill="" />
                                <path d="M713.8 667.5m-97.5 0a97.5 97.5 0 1 0 195 0 97.5 97.5 0 1 0-195 0Z"
                                    fill="#333E48" />
                                <path d="M713.8 667.5m-49.4 0a49.4 49.4 0 1 0 98.8 0 49.4 49.4 0 1 0-98.8 0Z"
                                    fill="#A4A9AD" />
                                <path
                                    d="M961.3 659.6c0 9.9-8.1 18-18 18h-40.5c-9.9 0-18-8.1-18-18v-29.2c0-9.9 8.1-18 18-18h40.5c9.9 0 18 8.1 18 18v29.2zM139.3 659.6c0 9.9-8.1 18-18 18H80.8c-9.9 0-18-8.1-18-18v-29.2c0-9.9 8.1-18 18-18h40.5c9.9 0 18 8.1 18 18v29.2z"
                                    fill="#A4A9AD" />
                                <path
                                    d="M458.5 379.2c0-8.5 7-15.5 15.5-15.5h126.9c8.5 0 15.5 7 15.5 15.5v69.9c0 8.5-7 15.5-15.5 15.5h-127c-8.5 0-15.5-7-15.5-15.5v-69.9zM199.7 378.9c1.4-8.4 9.6-15.3 18.1-15.3h138.5c8.5 0 15.5 7 15.5 15.5V449c0 8.5-7 15.5-15.5 15.5H200.7c-8.5 0-14.3-6.9-12.9-15.3l11.9-70.3z"
                                    fill="#333E48" />
                                <path
                                    d="M524.5 518.7H472c-8 0-14.5-6.5-14.5-14.5s6.5-14.5 14.5-14.5h52.5c8 0 14.5 6.5 14.5 14.5s-6.5 14.5-14.5 14.5zM242.1 518.7h-52.5c-8 0-14.5-6.5-14.5-14.5s6.5-14.5 14.5-14.5h52.5c8 0 14.5 6.5 14.5 14.5s-6.5 14.5-14.5 14.5z"
                                    fill="#00B3E3" />
                                <path
                                    d="M600.9 363.7h-127c-8.5 0-15.5 7-15.5 15.5v17.3c0-8.5 7-15.5 15.5-15.5h126.9c8.5 0 15.5 7 15.5 15.5v-17.3c0-8.6-6.9-15.5-15.4-15.5zM356.2 363.7H217.8c-8.5 0-16.6 6.9-18.1 15.3l-2.8 16.5c1.8-8 9.7-14.4 17.9-14.4h141.5c8.5 0 15.5 7 15.5 15.5v-17.3c-0.1-8.7-7-15.6-15.6-15.6z"
                                    fill="" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countInTransitVehicles() }} In Transit</h3>
                        <p class="mb-2"><a href="{{ route('vehicles.index', ['status' => 'In Transit']) }}"
                                target="_blank">In Transit</a></p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px"
                                viewBox="0 0 1024 1024" class="icon" version="1.1">
                                <path
                                    d="M863.68 390.848h-22.272a12.032 12.032 0 0 0-12.096 12.032v-212.48a12.032 12.032 0 0 0-12.032-12.032H280.256a12.032 12.032 0 0 0-12.032 12.032v212.544a12.032 12.032 0 0 0-12.032-12.032h-22.272a12.032 12.032 0 0 0-12.032 12.032V510.72c0 6.656 5.376 12.032 12.032 12.032h22.272a12.032 12.032 0 0 0 12.032-12.032v261.056c0 6.656 5.376 12.032 12.032 12.032h537.024a12.032 12.032 0 0 0 12.032-12.032V510.72c0 6.656 5.376 12.032 12.096 12.032h22.272a12.032 12.032 0 0 0 12.032-12.032V402.944a12.032 12.032 0 0 0-12.032-12.096z"
                                    fill="#E1B030" />
                                <path d="M342.208 659.584a44.224 42.304 0 1 0 88.448 0 44.224 42.304 0 1 0-88.448 0Z"
                                    fill="#FFFFFF" />
                                <path d="M666.944 659.584a44.224 42.304 0 1 0 88.448 0 44.224 42.304 0 1 0-88.448 0Z"
                                    fill="#FFFFFF" />
                                <path
                                    d="M318.144 783.872h100.288v64.128H318.144zM692.032 783.872h100.224v64.128h-100.224z"
                                    fill="#425760" />
                                <path
                                    d="M792.32 546.048a12.032 12.032 0 0 1-12.032 12.032H314.176a12.032 12.032 0 0 1-12.032-12.032V224.448c0-6.656 5.376-12.032 12.032-12.032h466.112c6.656 0 12.032 5.376 12.032 12.032v321.6z"
                                    fill="#959CC5" />
                                <path
                                    d="M792.32 301.056c0 1.728-5.376 3.136-12.032 3.136H314.176c-6.656 0-12.032-1.408-12.032-3.136V217.088c0-1.728 5.376-3.2 12.032-3.2h466.112c6.656 0 12.032 1.408 12.032 3.2v83.968z"
                                    fill="#586C77" />
                                <path
                                    d="M493.056 275.84c0 0.704-1.664 1.28-3.712 1.28H347.328c-1.984 0-3.648-0.576-3.648-1.28v-33.536c0-0.704 1.664-1.28 3.648-1.28h142.016c2.048 0 3.712 0.576 3.712 1.28v33.536zM609.472 275.84c0 0.704-0.896 1.28-1.856 1.28H533.056c-1.088 0-1.92-0.576-1.92-1.28v-33.536c0-0.704 0.896-1.28 1.92-1.28h74.56c1.024 0 1.856 0.576 1.856 1.28v33.536z"
                                    fill="#6CAA3D" />
                                <path
                                    d="M755.2 558.784h-32.128a56.128 56.128 0 0 0-112.192 0h-32.128c0-48.64 39.616-88.192 88.192-88.192 48.64 0.064 88.256 39.616 88.256 88.192z"
                                    fill="#2F428A" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countVehicles('WAITING FOR TRANSFERABLE TITLE') }}
                            Waiting For Transferable Title</h3>
                        <p class="mb-2"><a
                                href="{{ route('vehicles.index', ['status' => 'WAITING FOR TRANSFERABLE TITLE']) }}"
                                target="_blank">Waiting For Transferable Title</a></p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px"
                                viewBox="0 0 1024 1024" class="icon" version="1.1">
                                <path
                                    d="M915.136 617.024A390.528 390.528 0 0 0 618.88 258.304H135.872a45.44 45.44 0 0 0-45.44 45.44v413.12c0 25.088 20.352 45.44 45.44 45.44h779.776v-145.28h-0.512z"
                                    fill="#1AB1C7" />
                                <path d="M915.648 717.632h95.424v41.984h-95.424z" fill="#455963" />
                                <path
                                    d="M842.944 502.528c-15.872-76.288-69.056-134.464-121.28-146.688h-197.76c-10.24 0-18.624 7.232-18.624 16.192v147.136c0 8.96 8.384 16.192 18.624 16.192h319.296v-27.776l-0.256-5.056z"
                                    fill="#005E62" />
                                <path
                                    d="M608.832 763.264m-99.904 0a99.904 99.904 0 1 0 199.808 0 99.904 99.904 0 1 0-199.808 0Z"
                                    fill="#455963" />
                                <path
                                    d="M608.832 763.264m-51.712 0a51.712 51.712 0 1 0 103.424 0 51.712 51.712 0 1 0-103.424 0Z"
                                    fill="#AEBCC3" />
                                <path
                                    d="M385.728 762.304V356.032c0-14.912-10.432-27.008-23.232-27.008H184.64c-12.8 0-23.168 12.096-23.168 27.008v406.272h224.256z"
                                    fill="#85CEDA" />
                                <path
                                    d="M337.664 554.048c0 6.4-5.952 11.584-13.312 11.584H222.72c-7.36 0-13.312-5.184-13.312-11.584v-176c0-6.4 5.952-11.584 13.312-11.584h101.632c7.36 0 13.312 5.248 13.312 11.584v176z"
                                    fill="#005E62" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xxl d-flex">
        <div class="card flex-fill">
            <div class="card-body py-4">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-2"> {{ \App\Models\Vehicle::countVehicles('title rejected') }} Title Rejected
                        </h3>
                        <p class="mb-2"><a href="{{ route('vehicles.index', ['status' => 'Title Rejected']) }}"
                                target="_blank">Title Rejected</a></p>

                    </div>
                    <div class="d-inline-block ml-3">
                        <div class="stat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px"
                                viewBox="0 0 1024 1024" class="icon" version="1.1">
                                <path
                                    d="M822.144 210.432H118.208a38.08 38.08 0 0 0-38.016 38.144v456.768c0 20.992 17.024 38.08 38.016 38.08h796.864a38.144 38.144 0 0 0 38.144-38.08V393.472l-131.072-183.04z"
                                    fill="#AED0B1" />
                                <path
                                    d="M915.072 763.072H118.208a57.728 57.728 0 0 1-57.6-57.728V248.512c0-31.936 25.792-57.792 57.6-57.792h713.984L972.8 387.136v318.208a57.792 57.792 0 0 1-57.728 57.728zM118.208 230.08a18.432 18.432 0 0 0-18.368 18.432v456.768a18.56 18.56 0 0 0 18.368 18.432h796.864a18.56 18.56 0 0 0 18.432-18.432V399.872l-121.472-169.728H118.208z"
                                    fill="#579170" />
                                <path
                                    d="M382.592 479.296a17.792 17.792 0 0 1-17.664 17.664h-188.16a17.664 17.664 0 0 1-17.6-17.664V299.584c0-9.664 7.872-17.6 17.6-17.6h188.096c9.664 0 17.664 7.936 17.664 17.6v179.712z"
                                    fill="#BADBED" />
                                <path
                                    d="M364.928 508.8h-188.16a29.568 29.568 0 0 1-29.504-29.504V299.584c0-16.256 13.312-29.44 29.504-29.44h188.096a29.44 29.44 0 0 1 29.44 29.44v179.712a29.44 29.44 0 0 1-29.376 29.504z m-188.16-215.104a5.888 5.888 0 0 0-5.824 5.888V479.36c0 3.2 2.56 5.824 5.824 5.824h188.096a5.888 5.888 0 0 0 5.952-5.824V299.584a5.952 5.952 0 0 0-5.952-5.888H176.768z"
                                    fill="#579170" />
                                <path
                                    d="M660.672 479.296a17.664 17.664 0 0 1-17.6 17.664H454.976a17.728 17.728 0 0 1-17.664-17.664V299.584c0-9.664 7.936-17.6 17.664-17.6h188.096c9.728 0 17.6 7.936 17.6 17.6v179.712z"
                                    fill="#BADBED" />
                                <path
                                    d="M643.072 508.8H454.976a29.568 29.568 0 0 1-29.504-29.504V299.584a29.44 29.44 0 0 1 29.504-29.44h188.096a29.44 29.44 0 0 1 29.504 29.44v179.712a29.568 29.568 0 0 1-29.504 29.504zM454.976 293.696a5.888 5.888 0 0 0-5.888 5.888V479.36c0 3.2 2.624 5.824 5.888 5.824h188.096c3.2 0 5.888-2.624 5.888-5.824V299.584a5.952 5.952 0 0 0-5.888-5.888H454.976z"
                                    fill="#579170" />
                                <path
                                    d="M871.424 281.984h-138.304a17.664 17.664 0 0 0-17.664 17.6V479.36c0 9.6 7.936 17.6 17.664 17.6h188.032a17.664 17.664 0 0 0 17.664-17.6V379.712l-67.392-97.728z"
                                    fill="#BADBED" />
                                <path
                                    d="M921.216 508.8h-188.032a29.44 29.44 0 0 1-29.44-29.504V299.584a29.44 29.44 0 0 1 29.44-29.44h144.512l73.088 105.792v103.296a29.696 29.696 0 0 1-29.568 29.568z m-188.096-215.104a5.952 5.952 0 0 0-5.952 5.888V479.36c0 3.2 2.688 5.824 5.952 5.824h188.032a5.888 5.888 0 0 0 5.952-5.824V383.424l-61.824-89.728h-132.16z"
                                    fill="#579170" />
                                <path d="M99.968 557.312h63.552v73.344h-63.552z" fill="#E1B030" />
                                <path d="M869.632 557.312h63.616v73.344h-63.616z" fill="#FFFFFF" />
                                <path
                                    d="M239.296 742.784m-80.128 0a80.128 80.128 0 1 0 160.256 0 80.128 80.128 0 1 0-160.256 0Z"
                                    fill="#C5CDD5" />
                                <path
                                    d="M239.296 846.592a103.872 103.872 0 0 1-103.744-103.68c0-57.28 46.592-103.808 103.744-103.808s103.68 46.592 103.68 103.808c0 57.088-46.528 103.68-103.68 103.68z m0-160.32a56.64 56.64 0 0 0 0 113.152 56.576 56.576 0 0 0 0-113.152z"
                                    fill="#607385" />
                                <path
                                    d="M765.312 742.784m-80.128 0a80.128 80.128 0 1 0 160.256 0 80.128 80.128 0 1 0-160.256 0Z"
                                    fill="#C5CDD5" />
                                <path
                                    d="M765.248 846.592a103.808 103.808 0 0 1 0-207.488c57.28 0 103.744 46.592 103.744 103.808 0 57.088-46.464 103.68-103.744 103.68z m0-160.32a56.64 56.64 0 0 0 0 113.152 56.64 56.64 0 0 0 0-113.152z"
                                    fill="#607385" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
