@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Manager Table -->
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h3 class="text-white text-capitalize ps-3">{{ $title }} - Manager</h3>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{ route('salary-monitoring-approve') }}" method="POST">
                                @csrf
                                <table class="table table-sm table-striped table-hover align-items-center small-tbl compact"
                                    id="table-manager">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Year</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Month</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">No of employee</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Allowance</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Insentive & Overtime</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Total Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Average per person</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Approval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($counts as $month => $statuses)
                                            <tr>
                                                <td class="text-center">{{ $currentYear }}</td>
                                                <td class="text-center">{{ $month }}</td>
                                                <td class="text-center">{{ $statuses['Manager']['employee_count'] ?? 0 }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Manager']['total_rate_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Manager']['total_allowance'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Manager']['total_overtime_incentive'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Manager']['total_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Manager']['average_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="year" id=""
                                                        value="{{ $currentYear }}">
                                                    <input type="hidden" name="month" id=""
                                                        value="{{ $month }}">
                                                    <input type="hidden" name="status" id="" value="Manager">
                                                    <button class="btn btn-primary btn-icon-only m-0 p-0 btn-sm"
                                                        type="submit">
                                                        <span class="btn-inner--icon"><i
                                                                class="material-icons">thumb_up</i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>

                <br>

                <!-- Assistant Manager Table -->
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h3 class="text-white text-capitalize ps-3">{{ $title }} - Assistant Manager</h3>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{ route('salary-monitoring-approve') }}" method="POST">
                                <table class="table table-sm table-striped table-hover align-items-center small-tbl compact"
                                    id="table-assistant-manager">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Year</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Month</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">No of employee</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Allowance</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Insentive & Overtime</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Total Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Average per person</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Approval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($counts as $month => $statuses)
                                            <tr>
                                                <td class="text-center">{{ $currentYear }}</td>
                                                <td class="text-center">{{ $month }}</td>
                                                <td class="text-center">
                                                    {{ $statuses['Staff']['employee_count'] ?? 0 }}</td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Staff']['total_rate_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Staff']['total_allowance'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Staff']['total_overtime_incentive'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Staff']['total_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Staff']['average_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="year" id=""
                                                        value="{{ $currentYear }}">
                                                    <input type="hidden" name="month" id=""
                                                        value="{{ $month }}">
                                                    <input type="hidden" name="status" id="" value="Staff">
                                                    <button class="btn btn-primary btn-icon-only m-0 p-0 btn-sm"
                                                        type="submit">
                                                        <span class="btn-inner--icon"><i
                                                                class="material-icons">thumb_up</i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>

                <br>

                <!-- Monthly Table -->
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h3 class="text-white text-capitalize ps-3">{{ $title }} - Monthly</h3>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{ route('salary-monitoring-approve') }}" method="POST">
                                <table
                                    class="table table-sm table-striped table-hover align-items-center small-tbl compact"
                                    id="table-monthly">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Year</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Month</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">No of employee</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Allowance</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Insentive & Overtime</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Total Salary</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Average per person</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Approval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($counts as $month => $statuses)
                                            <tr>
                                                <td class="text-center">{{ $currentYear }}</td>
                                                <td class="text-center">{{ $month }}</td>
                                                <td class="text-center">{{ $statuses['Monthly']['employee_count'] ?? 0 }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Monthly']['total_rate_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Monthly']['total_allowance'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Monthly']['total_overtime_incentive'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Monthly']['total_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($statuses['Monthly']['average_salary'] ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center m-0 p-0">
                                                    <input type="hidden" name="year" id=""
                                                        value="{{ $currentYear }}">
                                                    <input type="hidden" name="month" id=""
                                                        value="{{ $month }}">
                                                    <input type="hidden" name="status" id="" value="Monthly">
                                                    <button class="btn btn-primary btn-icon-only m-0 p-0 btn-sm"
                                                        type="submit">
                                                        <span class="btn-inner--icon"><i
                                                                class="material-icons">thumb_up</i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
