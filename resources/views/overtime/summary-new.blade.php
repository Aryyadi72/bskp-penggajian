@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h3 class="text-white text-capitalize ps-3">{{ $title }}</h3>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="row">
                            <div class="col-9">
                                <table class="table table-bordered" style="width: 50px; height: 10px;">
                                    <thead>
                                        <tr>
                                            <th>Group</th>
                                            <th> : </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>Dept</th>
                                            <th> : </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>Month</th>
                                            <th> : </th>
                                            <th>{{ \Carbon\Carbon::parse($dateInput)->format('F') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-3">
                                <form action="{{ url('/overtime-summary-index') }}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col pe-0">
                                            <input type="month" name="month" id=""
                                                class="form-select form-select-sm">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <form action="{{ route('overtime-summary-store') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary mt-3">Submit Approval</button>
                                <table
                                    class="table table-sm table-striped table-hover dtTable2 align-items-center small-tbl compact"
                                    id="example">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Name</th>
                                            <th>Dept</th>
                                            <th>Status</th>
                                            <th>Jabatan</th>
                                            <th>Batas Lembur</th>
                                            @foreach ($dates as $date)
                                                <th>{{ \Carbon\Carbon::parse($date)->format('d') }}</th>
                                            @endforeach
                                            <th>Total Lembur (Before)</th>
                                            <th>Adjust</th>
                                            <th>Total Lembur (After)</th>
                                            <th>Nominal</th>
                                            <th>
                                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $nik => $records)
                                            @php
                                                $totalOvertime = $records->sum('hour_call');
                                                $adjust = 0;
                                                $adjustedOvertime = $totalOvertime - $adjust;
                                                $rateSalary = $records->first()->rate_salary;
                                                $ability = $records->first()->ability;
                                                $nominalUang = (($rateSalary + $ability) / 173) * $adjustedOvertime;
                                                $salaryYearsId = $records->first()->salary_years_id;
                                                $date = $records->first()->overtime_date;
                                            @endphp
                                            <tr>
                                                <td class="text-center p-0">{{ $loop->iteration }}</td>
                                                <td>{{ $records->first()->nik }}</td>
                                                <td>{{ $records->first()->name }}</td>
                                                <td>{{ $records->first()->dept }}</td>
                                                <td>{{ $records->first()->status }}</td>
                                                <td>{{ $records->first()->jabatan }}</td>
                                                <td>{{ $records->first()->overtime_limit ?? '0' }} Jam / Bulan</td>
                                                @foreach ($dates as $date)
                                                    <td class="text-center p-0">
                                                        {{ $records->firstWhere('overtime_date', $date)?->hour_call ?? 0 }}
                                                    </td>
                                                @endforeach
                                                <td class="text-center p-0">{{ $totalOvertime }}</td>
                                                <td class="text-center p-0">
                                                    <input type="number" name="adjust_{{ $nik }}" min="0"
                                                        value="0" style="width: 50px;"
                                                        oninput="calculateAdjust('{{ $nik }}', {{ $totalOvertime }}, {{ $rateSalary }}, {{ $ability }})">
                                                </td>
                                                <td id="adjusted_overtime_{{ $nik }}" class="text-center p-0">
                                                    {{ $totalOvertime }}</td>
                                                <td id="nominal_uang_{{ $nik }}" class="text-end">
                                                    {{ number_format((($rateSalary + $ability) / 173) * $totalOvertime, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="dates[]"
                                                        value="{{ \Carbon\Carbon::parse($records->first()->overtime_date)->format('Y-m-13') }}">
                                                    <input type="hidden" name="salary_years_id_{{ $nik }}"
                                                        value="{{ $records->first()->salary_years_id }}">
                                                    <input type="checkbox" id="checkbox_{{ $nik }}"
                                                        name="selected_items[]"
                                                        value="{{ $records->first()->salary_years_id }}|{{ $adjustedOvertime }}|{{ number_format($nominalUang, 2) }}">
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

        <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
        <script>
            document.getElementById('checkAll').onclick = function() {
                var checkboxes = document.querySelectorAll('input[name="selected[]"]');
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            }
        </script>
        <script>
            function toggleSelectAll(source) {
                checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = source.checked;
                });
            }

            function calculateAdjust(nik, totalOvertime, rateSalary, ability) {
                let adjust = parseFloat(document.querySelector(`input[name="adjust_${nik}"]`).value) || 0;
                if (adjust > totalOvertime) {
                    adjust = totalOvertime;
                    document.querySelector(`input[name="adjust_${nik}"]`).value = adjust;
                }
                let adjustedOvertime = totalOvertime - adjust;
                let nominalUang = ((rateSalary + ability) / 173) * adjustedOvertime;
                document.getElementById(`adjusted_overtime_${nik}`).textContent = adjustedOvertime;
                document.getElementById(`nominal_uang_${nik}`).textContent = nominalUang.toFixed(2);
                let checkbox = document.getElementById(`checkbox_${nik}`);
                if (checkbox) {
                    checkbox.value =
                        `${document.querySelector(`input[name="salary_years_id_${nik}"]`).value}|${adjustedOvertime}|${nominalUang.toFixed(2)}`;
                }
            }
        </script>
    @endsection
