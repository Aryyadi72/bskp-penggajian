@extends('layouts.main')
@section('content')
    <style>
        th.holiday-header {
            background-color: #ffcccc !important;
            color: #000 !important;
        }

        th.holiday-header:hover {
            background-color: #ffcccc !important;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-2 pb-1">
                            <h3 class="text-white text-capitalize ps-3">{{ $title }}</h3>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="row">
                            <div class="col-9">
                                <table style="border: 3px solid black; border-collapse: collapse;">
                                    <thead style="border: 3px solid black; border-collapse: collapse;">
                                        <tr style="border: 3px solid black; border-collapse: collapse;">
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                Group</th>
                                            <th style="border: 3px solid black; border-collapse: collapse;  padding: 5px;">
                                                : </th>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                Date</th>
                                            <th style="border: 3px solid black; border-collapse: collapse;  padding: 5px;">
                                                : </th>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                {{ $formattedMonth }}</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                Dept</th>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                : </th>
                                            <th style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-3">
                                <form action="{{ url('/overtime-summary-index') }}" method="GET">
                                    <div class="row">
                                        <div class="col pe-0">
                                            <input type="month" name="month" class="form-select form-select-sm"
                                                value="{{ request('month') }}">
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
                                            <th>Batas Lembur (Og)</th>
                                            <th>Jam Lembur (Og)</th>
                                            {{-- <th>Batas Lembur (Cal)</th> --}}
                                            @foreach ($dates as $dateInfo)
                                                <th class="{{ $dateInfo['isHoliday'] ? 'holiday-header' : '' }}">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="small">
                                                            {{ \Carbon\Carbon::parse($dateInfo['date'])->locale('id')->isoFormat('ddd') }}
                                                            {{-- @if ($dateInfo['isHoliday'])
                                                                ({{ \Carbon\Carbon::parse($dateInfo['date'])->format('d') }})
                                                            @endif --}}
                                                        </span>
                                                        <span
                                                            class="small">{{ \Carbon\Carbon::parse($dateInfo['date'])->format('d') }}</span>
                                                        @if ($dateInfo['isHoliday'])
                                                            <span class="small">{{ $dateInfo['holidayName'] }}</span>
                                                        @endif
                                                    </div>
                                                </th>
                                            @endforeach
                                            <th>Total Lembur (Before)</th>
                                            <th>Adjust</th>
                                            <th>Total Lembur (After)</th>
                                            <th>Nominal</th>
                                            <th>
                                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                                <label for="selectAll" class="small">Select All</label>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $nik => $records)
                                            @php
                                                $firstRecord = $records->first();
                                                $totalOvertime = $records->sum('hour_call');
                                                $rateSalary = $firstRecord->rate_salary;
                                                $ability = $firstRecord->ability;
                                                $otlimit = $firstRecord->overtime_limit;
                                                $salaryYearsId = $firstRecord->salary_years_id;
                                                $STANDARD_HOURS = 173;
                                                $adjust = 0;
                                                $adjustedOvertime = $totalOvertime - $adjust;
                                                $nominalUang =
                                                    (($rateSalary + $ability) / $STANDARD_HOURS) * $adjustedOvertime;
                                            @endphp
                                            <tr>
                                                <td class="text-center p-0">{{ $loop->iteration }}</td>
                                                <td>{{ $firstRecord->nik }}</td>
                                                <td>{{ $firstRecord->name }}</td>
                                                <td>{{ $firstRecord->dept }}</td>
                                                <td>{{ $firstRecord->status }}</td>
                                                <td>{{ $firstRecord->jabatan }}</td>
                                                <td>{{ $firstRecord->overtime_limit ?? '0' }} Jam</td>
                                                {{-- <td>{{ $otlimit * 2 }} Jam</td> --}}
                                                {{-- <td>{{ $firstRecord->hour_og }}</td> --}}
                                                <td>10</td>
                                                @foreach ($dates as $dateInfo)
                                                    <td class="text-center p-0">
                                                        {{ $records->firstWhere('overtime_date', $dateInfo['date'])?->hour_call ?? 0 }}
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
                                                    {{ number_format((($rateSalary + $ability) / $STANDARD_HOURS) * $totalOvertime, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="salary_years_id_{{ $nik }}"
                                                        value="{{ $salaryYearsId }}">
                                                    <input type="hidden" id="adjusted_overtime_hidden_{{ $nik }}"
                                                        name="adjusted_overtime_{{ $nik }}"
                                                        value="{{ $adjustedOvertime }}">
                                                    <input type="hidden" id="nominal_uang_hidden_{{ $nik }}"
                                                        name="nominal_uang_{{ $nik }}"
                                                        value="{{ number_format($nominalUang, 2) }}">
                                                    <input type="checkbox" id="checkbox_{{ $nik }}"
                                                        name="selected_items[]" value="{{ $nik }}">
                                                    <label for="checkbox_{{ $nik }}">Select</label>
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

        <!-- Include jQuery dan Bootstrap JS jika belum disertakan -->
        <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function toggleSelectAll(source) {
                const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = source.checked;
                });
            }

            function calculateAdjust(nik, totalOvertime, rateSalary, ability) {
                const STANDARD_HOURS = 173;
                let adjustInput = document.querySelector(`input[name="adjust_${nik}"]`);
                let adjust = parseFloat(adjustInput.value) || 0;

                // Mencegah nilai negatif
                if (adjust < 0) {
                    adjust = 0;
                    adjustInput.value = adjust;
                }

                // Mencegah penyesuaian lebih besar dari total lembur
                if (adjust > totalOvertime) {
                    adjust = totalOvertime;
                    adjustInput.value = adjust;
                }

                let adjustedOvertime = totalOvertime - adjust;
                let nominalUang = ((rateSalary + ability) / STANDARD_HOURS) * adjustedOvertime;

                document.getElementById(`adjusted_overtime_${nik}`).textContent = adjustedOvertime;
                document.getElementById(`nominal_uang_${nik}`).textContent = nominalUang.toFixed(2);

                // Update hidden inputs
                document.getElementById(`adjusted_overtime_hidden_${nik}`).value = adjustedOvertime;
                document.getElementById(`nominal_uang_hidden_${nik}`).value = nominalUang.toFixed(2);
            }
        </script>
    @endsection
