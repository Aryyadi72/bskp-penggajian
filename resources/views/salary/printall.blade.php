<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print All</title>

    {{-- <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        .tb-style th,
        td {
            border: 1px solid #000;
            border-collapse: collapse;
            width: 100%;
            font-size: 5pt;
        }

        .tb-detail {
            border-collapse: collapse;
            width: 100%;
            font-size: 5pt;
        }

        .tb-detail th,
        .tb-detail td {
            border: none;
            width: auto;
        }

        .dash-line {
            border: none;
            border-top: 2px dashed #888;
        }

        .tb-noborder th,
        .tb-noborder td {
            border: none;
            width: auto;
        }

        .tb-collapse table,
        th,
        td {
            width: 100%;
            border: 0.1px solid #000;
            border-collapse: collapse;
            font-size: 6pt;
            padding: 0px 2px 0px 2px
        }

        .outline-border {
            border: 1px solid black;
            padding: 8px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-end {
            text-align: right;
        }

        .page-break {
            page-break-before: always;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            line-height: 35px;
            font-size: 9pt;
        }

        .pagenum:before {
            counter-increment: currentPage;
            content: counter(currentPage) " of 5";
        }

        .total-column {
            padding: 8px; /* Atur lebar sesuai keinginan */
        }

        thead {
            display: table-header-group; /* Ensures header repeats on each page */
        }

        /* #pageCounter {
        counter-reset: pageTotal;
        }
        #pageCounter span {
        counter-increment: pageTotal;
        }
        #pageNumbers {
        counter-reset: currentPage;
        }
        #pageNumbers div:before {
        counter-increment: currentPage;
        content: "Page " counter(currentPage) " of ";
        }
        #pageNumbers div:after {
        content: counter(pageTotal);
        } */

    </style> --}}

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        table{
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 5pt;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 5pt;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 5pt;
            border-collapse: collapse;
        }

        .tb-noborder th,
        .tb-noborder td {
            border: none;
            width: auto;
        }
    </style>
</head>

<body>

    <div style="text-align: center">
        <h1>PT BRIDGESTONE KALIMANTAN PLANTATION</h1>
        <h1>SALARY PAYMENT {{ $date }}</h1>
    </div>

    <footer>
        <span class="pagenum"></span>
    </footer>

    @php
        $grandTotal = 0;
        $firstIteration = true;
    @endphp

    @foreach ($salaries as $status => $salaries)
        @if (!$firstIteration)
            <div class="page-break"></div>
        @else
            @php
                $firstIteration = false;
            @endphp
        @endif



        {{-- <hr class="dash-line"> --}}
        <div class="tb-collapse" id="pageCounter">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th colspan="3">Employee Identity</th>
                        <th colspan="10">Salary Component</th>
                        {{-- <th rowspan="2">Salary Gross + Benefite</th> --}}
                        <th colspan="7">Deduction</th>
                        {{-- <th rowspan="2">Total Deduction + Benefite Deduction</th> --}}
                        {{-- <th rowspan="2">Adjustment</th> --}}
                        <th rowspan="2">Net Salary</th>
                    </tr>
                    <tr>
                        <th width="35px">Emp Code</th>
                        <th width="100px">Name</th>
                        {{-- <th>Dept</th> --}}
                        {{-- <th>Job</th> --}}
                        <th width="">Grade</th>
                        <th>Salary Grade</th>
                        <th>Ability</th>
                        <th>Fungtional All</th>
                        <th>Family<br>All</th>
                        <th>Transport<br>All</th>
                        <th>Skill<br>All</th>
                        <th>Telephone<br>All</th>
                        <th>Total Overtime</th>
                        {{-- <th>THR</th> --}}
                        {{-- <th>Bonus</th> --}}
                        <th>Incentive</th>
                        <th>Salary Gross</th>
                        {{-- <th>Salary gross + Benefite</th> --}}
                        <th>BPJS Kesehatan</th>
                        <th>Jamsostek</th>
                        <th>Union</th>
                        <th>Absent</th>
                        <th>Electricity</th>
                        <th>Koperasi</th>
                        <th>Sub Total Deduction</th>
                        {{-- <th>Total Deduction</th> --}}
                        {{-- <th>Net Salary</th> --}}
                    </tr>

                    {{-- <tr>
                        <td colspan="25"> {{ $status }}</td>
                    </tr> --}}
                </thead>

                @php
                    $rate_salary_t = 0;
                    $ability_t = 0;
                    $fungtional_alw_t = 0;
                    $family_alw_t = 0;
                    $transport_alw_t = 0;
                    $telephone_alw_t = 0;
                    $skill_alw_t = 0;
                    $adjustment_t = 0;
                    $total_overtime_t = 0;
                    $thr_t = 0;
                    $bonus_t = 0;
                    $incentive_t = 0;
                    $gross_salary_t = 0;
                    $total_ben_t = 0;
                    $bpjs_t = 0;
                    $jamsostek_t = 0;
                    $union_t = 0;
                    $absent_t = 0;
                    $electricity_t = 0;
                    $cooperative_t = 0;
                    $total_deduction_t = 0;
                    $total_ben_ded_t = 0;
                    $subtotal = 0;
                @endphp

                @foreach ($salaries as $key => $sal)
                    <tr>
                        <td style="text-align: center">{{ $key }}</td>
                        <td>{{ $sal->nik }}</td>
                        <td>{{ $sal->name }}</td>
                        {{-- <td>{{ $sal->salary_year->user->dept->name_dept }}</td> --}}
                        {{-- <td>{{ $sal->salary_year->user->job->name_job }}</td> --}}
                        <td>{{ $sal->name_grade }}</td>

                        <td style="text-align: right;">
                            {{ number_format($sal->rate_salary, 0, ',', '.') }}</td>

                        @if ($sal->ability == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->ability, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->fungtional_alw == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->fungtional_alw, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->family_alw == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->family_alw, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->transport_alw == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->transport_alw, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->skill_alw == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->skill_alw, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->telephone_alw == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->telephone_alw, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->total_overtime == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->total_overtime, 0, ',', '.')}}</td>
                        @endif

                        {{-- @if ($sal->thr == 0)
                            <td class="text-end">-</td>
                        @else
                            <td class="text-end">{{ number_format($sal->thr, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->bonus == 0)
                            <td class="text-end">-</td>
                        @else
                            <td class="text-end">{{ number_format($sal->bonus, 0, ',', '.')}}</td>
                        @endif --}}

                        @if ($sal->incentive == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->incentive, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->gross_salary == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->gross_salary, 0, ',', '.')}}</td>
                        @endif

                        {{-- <td style="text-align: right;">
                            {{ number_format($sal->gross_salary + $sal->salary_year->total_ben, 0, ',', '.') }}
                        </td> --}}

                        <td style="text-align: right;">{{ number_format($sal->bpjs, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($sal->jamsostek, 0, ',', '.') }}</td>

                        @if ($sal->union == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->union, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->absent == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->absent, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->electricity == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->electricity, 0, ',', '.')}}</td>
                        @endif

                        @if ($sal->cooperative == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->cooperative, 0, ',', '.')}}</td>
                        @endif

                        <td style="text-align: right;">{{ number_format($sal->total_deduction, 0, ',', '.') }}</td>

                        {{-- <td style="text-align: right;">
                            {{ number_format($sal->total_deduction + $sal->salary_year->total_ben_ded, 0, ',', '.') }}
                        </td> --}}

                        {{-- @if ($sal->salary_year->adjustment == 0)
                            <td style="text-align: right;">-</td>
                        @else
                            <td style="text-align: right;">{{ number_format($sal->salary_year->adjustment, 0, ',', '.')}}</td>
                        @endif --}}

                        <td style="text-align: right;">{{ number_format($sal->net_salary, 0, ',', '.') }}</td>

                    </tr>

                    @php
                        $rate_salary_t += $sal->salary_year->salary_grade->rate_salary;
                        $ability_t += $sal->salary_year->ability;
                        $fungtional_alw_t += $sal->salary_year->fungtional_alw;
                        $family_alw_t += $sal->salary_year->family_alw;
                        $skill_alw_t += $sal->salary_year->skill_alw;
                        $telephone_alw_t += $sal->salary_year->telephone_alw;
                        $adjustment_t += $sal->salary_year->adjustment;
                        $transport_alw_t += $sal->salary_year->transport_alw;
                        $total_overtime_t += $sal->total_overtime;
                        $thr_t += $sal->thr;
                        $bonus_t += $sal->bonus;
                        $incentive_t += $sal->incentive;
                        $gross_salary_t += $sal->gross_salary;
                        $total_ben_t += $sal->salary_year->total_ben;
                        $bpjs_t += $sal->salary_year->bpjs;
                        $jamsostek_t += $sal->salary_year->jamsostek;
                        $union_t += $sal->union;
                        $absent_t += $sal->absent;
                        $electricity_t += $sal->electricity;
                        $cooperative_t += $sal->cooperative;
                        $total_deduction_t += $sal->total_deduction;
                        $total_ben_ded_t += $sal->salary_year->total_ben_ded;
                        $subtotal += $sal->net_salary; // Menambahkan net_salary barang ke subtotal
                    @endphp
                @endforeach
                <tr>
                    <td colspan="4" class="total-column">Total</td>
                    <td style="text-align: right;">{{ number_format($rate_salary_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($ability_t, 0, ',', '.')}}</td>
                    <td style="text-align: right;">{{ number_format($fungtional_alw_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($family_alw_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($transport_alw_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($skill_alw_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($telephone_alw_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($total_overtime_t, 0, ',', '.') }}</td>
                    {{-- <td class="text-end">{{ number_format($thr_t, 0, ',', '.') }}</td> --}}
                    {{-- <td class="text-end">{{ number_format($bonus_t, 0, ',', '.') }}</td> --}}
                    <td style="text-align: right;">{{ number_format($incentive_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($gross_salary_t, 0, ',', '.') }}</td>
                    {{-- <td style="text-align: right;">{{ number_format($gross_salary_t + $total_ben_t, 0, ',', '.') }}</td> --}}
                    <td style="text-align: right;">{{ number_format($bpjs_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($jamsostek_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($union_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($absent_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($electricity_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($cooperative_t, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($total_deduction_t, 0, ',', '.') }}</td>
                    {{-- <td style="text-align: right;">{{ number_format($total_deduction_t + $total_ben_ded_t, 0, ',', '.') }}</td> --}}
                    {{-- <td class="text-end">{{ number_format($adjustment_t, 0, ',', '.') }}</td> --}}
                    <td style="text-align: right;">{{ number_format($subtotal, 0, ',', '.') }}</td>
                    {{-- <td>{{ number_format($salaries->sum('net_salary'), 0, ',', '.') }}</td> --}}
                </tr>
                @php
                    $grandTotal += $subtotal; // Menambahkan subtotal ke grand total
                @endphp
                {{-- <tr style="background: #dddddd">
                <td colspan="25">Grand Total</td>
                <td>{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr> --}}
            </table>
        </div>
    @endforeach

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var totalPages = Math.ceil(document.body.scrollHeight / window.innerHeight);
            document.querySelectorAll(".total-pages").forEach(function(el) {
                el.textContent = totalPages;
            });
        });
    </script>
    <script>
        window.print();
    </script>

</body>

</html>
