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
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
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
                                                    <input type="checkbox" name="approval_1" class="approval-check"
                                                        id="approval1-{{ $month }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_2" class="approval-check"
                                                        id="approval2-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_3" class="approval-check"
                                                        id="approval3-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_4" class="approval-check"
                                                        id="approval4-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_5" class="approval-check"
                                                        id="approval5-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_6" class="approval-check"
                                                        id="approval6-{{ $month }}" disabled>
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
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
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
                                                    <input type="checkbox" name="approval_1" class="approval-check"
                                                        id="approval1-{{ $month }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_2" class="approval-check"
                                                        id="approval2-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_3" class="approval-check"
                                                        id="approval3-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_4" class="approval-check"
                                                        id="approval4-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_5" class="approval-check"
                                                        id="approval5-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_6" class="approval-check"
                                                        id="approval6-{{ $month }}" disabled>
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
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
                                            <th style="background-color: #1A73E8; color: white; font-size: 12px;"
                                                class="text-center">Check</th>
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
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_1" class="approval-check"
                                                        id="approval1-{{ $month }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_2" class="approval-check"
                                                        id="approval2-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_3" class="approval-check"
                                                        id="approval3-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_4" class="approval-check"
                                                        id="approval4-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_5" class="approval-check"
                                                        id="approval5-{{ $month }}" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="approval_6" class="approval-check"
                                                        id="approval6-{{ $month }}" disabled>
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

    <script>
        // Function to enable checkboxes dynamically
        document.addEventListener('DOMContentLoaded', function() {
            // Get all rows
            document.querySelectorAll('tbody tr').forEach(function(row) {
                // Get all checkboxes for the current row
                const checkbox1 = row.querySelector('[id^="approval1-"]');
                const checkbox2 = row.querySelector('[id^="approval2-"]');
                const checkbox3 = row.querySelector('[id^="approval3-"]');
                const checkbox4 = row.querySelector('[id^="approval4-"]');
                const checkbox5 = row.querySelector('[id^="approval5-"]');
                const checkbox6 = row.querySelector('[id^="approval6-"]');

                // Add event listener to the first checkbox
                checkbox1.addEventListener('change', function() {
                    if (this.checked) {
                        checkbox2.disabled = false; // Enable second checkbox
                    } else {
                        checkbox2.disabled = true;
                        checkbox3.disabled = true;
                        checkbox4.disabled = true;
                        checkbox5.disabled = true;
                        checkbox6.disabled = true;
                        checkbox2.checked = false;
                        checkbox3.checked = false;
                        checkbox4.checked = false;
                        checkbox5.checked = false;
                        checkbox6.checked = false;
                    }
                });

                // Add event listener to the second checkbox
                checkbox2.addEventListener('change', function() {
                    if (this.checked) {
                        checkbox3.disabled = false; // Enable third checkbox
                    } else {
                        checkbox3.disabled = true;
                        checkbox4.disabled = true;
                        checkbox5.disabled = true;
                        checkbox6.disabled = true;
                        checkbox3.checked = false;
                        checkbox4.checked = false;
                        checkbox5.checked = false;
                        checkbox6.checked = false;
                    }
                });

                // Add event listener to the third checkbox
                checkbox3.addEventListener('change', function() {
                    if (this.checked) {
                        checkbox4.disabled = false; // Enable fourth checkbox
                    } else {
                        checkbox4.disabled = true;
                        checkbox5.disabled = true;
                        checkbox6.disabled = true;
                        checkbox4.checked = false;
                        checkbox5.checked = false;
                        checkbox6.checked = false;
                    }
                });

                // Add event listener to the fourth checkbox
                checkbox4.addEventListener('change', function() {
                    if (this.checked) {
                        checkbox5.disabled = false; // Enable fifth checkbox
                    } else {
                        checkbox5.disabled = true;
                        checkbox6.disabled = true;
                        checkbox5.checked = false;
                        checkbox6.checked = false;
                    }
                });

                // Add event listener to the fifth checkbox
                checkbox5.addEventListener('change', function() {
                    if (this.checked) {
                        checkbox6.disabled = false; // Enable sixth checkbox
                    } else {
                        checkbox6.disabled = true;
                        checkbox6.checked = false;
                    }
                });
            });
        });
    </script>
@endsection
