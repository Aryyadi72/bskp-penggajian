@php
    $currentRoute = Route::currentRouteName();
@endphp

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">

    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard "
            target="_blank">
            <img src="{{ asset('/assets/img/bridgestone_outline.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white">BSKP-Penggajian</span>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">

    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'dashboard.index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'user') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ route('user.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">groups</i>
                    </div>
                    <span class="nav-link-text ms-1">Employees Data</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salarygrade') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ route('grade.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">price_change</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary Data - Per Grade</span>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dataMaseter"
                    class="nav-link text-white {{ Str::startsWith($currentRoute, ['status', 'grade', 'departement', 'job']) ? 'active' : '' }}"
                    aria-controls="dataMaseter" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">storage</i>
                    <span class="nav-link-text ms-2 ps-1">Master Data</span>
                </a>
                <div class="collapse {{ Str::startsWith($currentRoute, ['status', 'grade', 'departement', 'job', 'salarygrade']) ? 'show' : '' }}"
                    id="dataMaseter" style="">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'status') ? 'active bg-gradient-primary' : '' }}"
                                href="{{ route('status.index') }}">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Status </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'departement') ? 'active bg-gradient-primary' : '' }}"
                                href="{{ route('departement.index') }}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Departement </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'job') ? 'active bg-gradient-primary' : '' }}"
                                href="{{ route('job.index') }}">
                                <span class="sidenav-mini-icon"> J </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Job </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salarygrade') ? 'active bg-gradient-primary' : '' }}"
                                href="{{ route('grade.index') }}">
                                <span class="sidenav-mini-icon"> <i class="material-icons">price_change</i> </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Salary Data - Per Grade </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Salary Data
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salary-year') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/salary-year') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">payments</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary Data - Per Year</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salary-month') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/salary-month') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">payments</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary Data - Per Month</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salary.index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/salary') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">All Salary Data</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Salary Option
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salary.print_index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/print-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">print</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary print</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'list-is-send') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/list-is-send') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">mail</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary Send</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Summary Salary
                </h6>
            </li>

            @if (in_array('Inputer', $roles))
                <li class="nav-item">
                    <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'historical') ? 'active bg-gradient-primary' : '' }}"
                        href="{{ url('/historical') }}?token={{ session('jwt_token') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons">grade</i>
                        </div>
                        <span class="nav-link-text ms-1">Historical</span>
                    </a>
                </li>
            @endif

            {{-- <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'historical') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/historical') }}?token={{ session('jwt_token') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">grade</i>
                    </div>
                    <span class="nav-link-text ms-1">Historical</span>
                </a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'summary') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/summary') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">Summary</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'salary-monitoring') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/salary-monitoring') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">monitor</i>
                    </div>
                    <span class="nav-link-text ms-1">Salary Monitoring</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Overtime Data
                </h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'overtime-limit-index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/overtime-limit-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">alarm</i>
                    </div>
                    <span class="nav-link-text ms-1">Overtime Limit</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'overtime-master-index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/overtime-master-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">manage_history</i>
                    </div>
                    <span class="nav-link-text ms-1">Overtime Master</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'overtime-approval-index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/overtime-approval-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">schedule</i>
                    </div>
                    <span class="nav-link-text ms-1">Overtime Approval</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ Str::startsWith($currentRoute, 'overtime-summary-index') ? 'active bg-gradient-primary' : '' }}"
                    href="{{ url('/overtime-summary-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons">history</i>
                    </div>
                    <span class="nav-link-text ms-1">Summary Overtime</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="./sign-in.html">

                    <div class="text-danger text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">logout</i>
                    </div>

                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>

</aside>
