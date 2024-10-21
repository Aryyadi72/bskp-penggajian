@extends('layouts.main')
@section('content')
    <style>
        .sent {
            color: green;
        }

        .not-sent {
            color: red;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h5 class="text-white text-capitalize ps-3">{{ $title }}</h5>
                        </div>
                    </div>

                    <div>
                        <div class="card-body p-3 pb-2">
                            <div class="row">
                                <div class="col-7">
                                    <table style="border: 3px solid black; border-collapse: collapse;">
                                        <thead style="border: 3px solid black; border-collapse: collapse;">
                                            <tr style="border: 3px solid black; border-collapse: collapse;">
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                    Group</th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse;  padding: 5px;">
                                                    : </th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                    Date</th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse;  padding: 5px;">
                                                    : </th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                    {{ \Carbon\Carbon::parse($dateYesterday)->format('l, d-m-Y') }}</th>
                                            </tr>
                                            <tr>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                    Dept</th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                    : </th>
                                                <th
                                                    style="border: 3px solid black; border-collapse: collapse; padding: 5px;">
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-5 justify-content-end">
                                    <form action="{{ url('/overtime-approval-index') }}" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="col pe-0">
                                                <input type="date" name="date" id=""
                                                    class="form-select form-select-sm">
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                    <form method="POST" action="{{ route('overtime-approval-store') }}">
                        @csrf
                        <input type="hidden" id="inputDate" name="tanggal" class="form-control"
                            value="{{ \Carbon\Carbon::parse($dateYesterday)->format('Y-m-d') }}">
                        <div class="card-body p-3 pb-2">
                            <button type="submit" class="btn btn-primary mt-3">Submit Approval</button>
                            @if (isset($dataGabunganGrouped) && $dataGabunganGrouped->isNotEmpty())
                                @foreach ($dataGabunganGrouped as $status => $items)
                                    <div class="my-4">
                                        <h6>Status: {{ $status }}</h6>
                                        <table
                                            class="table table-sm table-striped table-hover align-items-center compact small-tbl dtTable2">
                                            <thead class="bg-thead">
                                                <tr>
                                                    <th rowspan="2" class="text-center"
                                                        style="background-color: #1A73E8;color: white;">
                                                        <input type="checkbox" id="selectAll"
                                                            onclick="toggleSelectAll(this)">
                                                    </th>
                                                    <th rowspan="2" class="text-center">No</th>
                                                    <th rowspan="2" class="text-center">NIK</th>
                                                    <th rowspan="2" class="text-center">Name</th>
                                                    <th rowspan="2" class="text-center">Dept</th>
                                                    <th rowspan="2" class="text-center">Status</th>
                                                    <th rowspan="2" class="text-center">Jabatan</th>
                                                    <th colspan="3" class="text-center">Overtime</th>
                                                    {{-- <th rowspan="2" class="text-center">Approve</th> --}}
                                                </tr>
                                                <tr>
                                                    <th class="text-center">OG</th>
                                                    {{-- <th class="text-center">Jam</th> --}}
                                                    {{-- <th class="text-center">Menit</th> --}}
                                                    <th class="text-center">OT (Cal)</th>
                                                    <th class="text-center">Kalkulasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $index => $item)
                                                    @php
                                                        $rate_salary = $item['rate_salary'] ?? 0;
                                                        $ability = $item['ability'] ?? 0;
                                                        $overtime_hour = $item['overtime_hour'] ?? 0;
                                                        $overtime_minute = $item['overtime_minute'] ?? 0;
                                                        $overtime_hour_after_cal =
                                                            $item['overtime_hour_after_cal'] ?? 0;
                                                        $totalOvertime =
                                                            (($rate_salary + $ability) / 173) *
                                                            $overtime_hour_after_cal;
                                                        $id_salary_year = $item['id_salary_year'] ?? '';
                                                        $isApproved = $item['is_approved'] == 1 ? 'Yes' : 'No';
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="select_item[]"
                                                                value="{{ $item['user_id'] }}" class="selectItem"
                                                                onclick="toggleRow(this, {{ $index }})"
                                                                {{ $item['is_approved'] == 1 ? 'disabled' : '' }}>
                                                        </td>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td class="text-center">{{ $item['user_id'] }}</td>
                                                        <td>{{ $item['name'] ?? 'Nama tidak ditemukan' }}</td>
                                                        <td>{{ $item['dept'] ?? 'Dept tidak ditemukan' }}</td>
                                                        <td>{{ $item['status'] ?? 'Status tidak ditemukan' }}</td>
                                                        <td>{{ $item['jabatan'] ?? 'Jabatan tidak ditemukan' }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($item['total_minutes_in_decimal'], 2) }}
                                                        </td>
                                                        {{-- <td class="text-center">{{ $overtime_hour }}</td> --}}
                                                        {{-- <td class="text-center">{{ $overtime_minute }}</td> --}}
                                                        {{-- <td class="text-center">{{ $overtime_hour_after_cal }}</td> --}}
                                                        {{-- <td class="text-center">{{ number_format($totalOvertime) }}</td> --}}
                                                        <input type="hidden"
                                                            name="original_overtime[{{ $item['user_id'] }}]"
                                                            value="{{ number_format($item['total_minutes_in_decimal'], 2) }}">
                                                        <td class="text-center">
                                                            <input type="number"
                                                                name="adjust_ot_call[{{ $item['user_id'] }}]"
                                                                value="{{ $overtime_hour_after_cal }}" min="0"
                                                                step="0.01" style="width: 50px;"
                                                                data-rate-salary="{{ $rate_salary }}"
                                                                data-ability="{{ $ability }}"
                                                                oninput="updateTotalOvertime('{{ $item['user_id'] }}', this.value)">
                                                        </td>
                                                        <!-- Kolom Kalkulasi dengan ID unik -->
                                                        <td class="text-center">
                                                            <input type="text"
                                                                id="total_overtime_{{ $item['user_id'] }}"
                                                                value="{{ number_format($totalOvertime, 2) }}" readonly
                                                                style="width: 70px;">
                                                        </td>
                                                        {{-- <td class="text-center">
                                                            @if ($isApproved == 'Yes')
                                                                <span class="sent">✓</span>
                                                            @else
                                                                <span class="not-sent">✗</span>
                                                            @endif
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @else
                                <p>Tidak ada data yang cocok untuk tanggal ini.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/tableToExcel.js') }}"></script>
        <script>
            $("#btn-d").click(function() {
                TableToExcel.convert(document.getElementById("example"), {
                    name: "All Salary Data.xlsx",
                });
            });
        </script>

        <script>
            /**
             * Menghitung ulang nilai Kalkulasi berdasarkan OT Call yang diubah.
             *
             * @param {string} userId - ID unik pengguna.
             * @param {number} hourCall - Nilai OT Call yang diinputkan.
             */
            function updateTotalOvertime(userId, hourCall) {
                // Cari elemen input yang mengandung data-rate-salary dan data-ability
                const adjustInput = document.querySelector(`input[name="adjust_ot_call[${userId}]"]`);
                if (!adjustInput) return;

                // Ambil data-rate-salary dan data-ability
                const rateSalary = parseFloat(adjustInput.getAttribute('data-rate-salary')) || 0;
                const ability = parseFloat(adjustInput.getAttribute('data-ability')) || 0;

                // Parsing nilai OT Call
                const parsedHourCall = parseFloat(hourCall) || 0;

                // Hitung total overtime berdasarkan formula
                const totalOvertime = ((rateSalary + ability) / 173) * parsedHourCall;

                // Cari elemen Kalkulasi berdasarkan ID unik
                const kalkulasiInput = document.getElementById(`total_overtime_${userId}`);
                if (kalkulasiInput) {
                    kalkulasiInput.value = totalOvertime.toFixed(2); // Pembulatan 2 desimal
                }
            }
        </script>


        <script>
            function toggleSelectAll(source) {
                const checkboxes = document.querySelectorAll('.selectItem');
                checkboxes.forEach((checkbox, index) => {
                    if (!checkbox.disabled) {
                        checkbox.checked = source.checked;
                        toggleRow(checkbox, index);
                    }
                });
            }

            function toggleRow(checkbox, index) {
                const form = checkbox.closest('form');
                if (checkbox.checked) {
                    addInputFields(form, index);
                } else {
                    removeInputFields(form, index);
                }
            }

            function addInputFields(form, index) {
                const userId = form.querySelectorAll('.selectItem')[index].value;
                const overtimeHour = document.querySelectorAll('tbody tr')[index].children[9].textContent.trim();
                const totalOvertime = document.querySelectorAll('tbody tr')[index].children[10].textContent.trim().replace(',',
                    '');

                addHiddenInput(form, 'user_id[]', userId);
                addHiddenInput(form, 'overtime_hour_after_cal[]', overtimeHour);
                addHiddenInput(form, 'totalOvertime[]', totalOvertime);
            }

            function addHiddenInput(form, name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            }

            function removeInputFields(form, index) {
                const userId = form.querySelectorAll('.selectItem')[index].value;
                removeHiddenInput(form, 'user_id[]', userId);
                removeHiddenInput(form, 'overtime_hour_after_cal[]', userId);
                removeHiddenInput(form, 'totalOvertime[]', userId);
            }

            function removeHiddenInput(form, name, value) {
                const inputs = form.querySelectorAll(`input[name="${name}"]`);
                inputs.forEach(input => {
                    if (input.value == value) {
                        form.removeChild(input);
                    }
                });
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const hourCallInputs = document.querySelectorAll('[name^="overtime_hour_after_cal["]');

                hourCallInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        calculateTotalOvertime(this);
                    });
                });

                function calculateTotalOvertime(input) {
                    const key = input.name.match(/\[(\d+)\]/)[1];
                    const rateSalary = parseFloat(document.querySelector(`[name="rate_salary[${key}]"]`).value) || 0;
                    const ability = parseFloat(document.querySelector(`[name="ability[${key}]"]`).value) || 0;

                    const totalOvertimeInput = document.querySelector(`[name="total_overtime[${key}]"]`);

                    if (!isNaN(rateSalary) && !isNaN(ability)) {
                        const hourCall = parseFloat(input.value) || 0;
                        const totalOvertime = ((rateSalary + ability) / 173) * hourCall;
                        totalOvertimeInput.value = totalOvertime.toFixed(2);
                    } else {
                        totalOvertimeInput.value = '';
                    }
                }
            });
        </script>
    </div>
@endsection
