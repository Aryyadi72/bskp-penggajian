<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        SAL_
    </title>

    <!-- CSS Files -->
    {{-- <link id="pagestyle" href="{{ public_path ('assets/libs/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet" /> --}}
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 1.5;
        }

        .tb-detail {
            border-collapse: collapse;
            width: 100%;
            font-size: 9pt;
        }

        .tb-detail th,
        .tb-detail td {
            border: none;
            width: auto;
        }

        .right-border {
            border-top: 1px solid #000;
        }

        .left-border {
            border-top: 1px solid #000;
        }

        .buttom-border {
            border-top: 1px solid #000;
        }

        .top-border {
            border-top: 1px solid #000;
        }

        .dash-line {
            border: none;
            border-top: 2px dashed #888;
        }

        .table-collapse table,
        th,
        td {
            width: 100%;
            border: 1px none #000;
            border-collapse: collapse;
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
    </style>
</head>

<body>
    @foreach ($pdfData as $data)
        <div class="table-collapse">
            <table>
                <tr>
                    <td>PT BRIDGESTONE KALIMANTAN PLANTATION</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Bentok Darat, Bati-Bati, Kab.Tanah Laut</td>
                    <td align="right" class="uppercase">SALARY PAYMENT
                        {{ date('F Y', strtotime($data['sal']->salary_months_date)) }}
                </tr>
                <tr>
                    <td> <u>Kalimantan Selatan - 70852</u></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <hr class="dash-line">

        <div class="table-collapse">
            <table>
                <tr>
                    <td>
                        <table class="tb-detail">
                            <tr>
                                <td>Employe Code </td>
                                <td> : {{ $data['sal']->Emp_Code }}</td>
                            </tr>

                            <tr>
                                <td>Grade</td>
                                <td>: {{ $data['sal']->Grade }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: {{ $data['sal']->Status }}</td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table class="tb-detail">
                            <tr>
                                <td>Employe Name</td>
                                <td>: {{ $data['sal']->Nama }}</td>
                            </tr>
                            <tr>
                                <td>Departement</td>
                                <td>: {{ $data['sal']->Dept }}</td>
                            </tr>
                            <tr>
                                <td>Job</td>
                                <td>: {{ $data['sal']->Jabatan }}</td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
        </div>

        <hr class="dash-line">

        <div class="table-collapse">
            <table>
                <tr>
                    <td rowspan="2" style="vertical-align: top; padding-right: 10px;  padding-bottom: 0;">
                        <table class="tb-detail">
                            <tr>
                                <td colspan="3"><u><b>A. SALARY COMPONENT</b></u></td>
                            </tr>
                            <tr>
                                <td>Grade</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->rate_salary, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Ability</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->ability, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Fungtional All</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->fungtional_alw, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Family All</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->family_alw, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td>Transport All</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->transport_alw, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Skill All</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->skill_alw, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Telephone All</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->telephone_alw, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Overtime</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->total_overtime, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>THR</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->thr, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Bonus</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->bonus, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Incentive</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->incentive, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Adjustment</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->adjustment, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="top-border">
                                <td><b>Salary Gross</b></td>
                                <td>:</td>
                                <td class="text-end"><b>
                                        {{ number_format($data['sal']->gross_salary, 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="padding-left: 10px; padding-right: 10px;">
                        <table class="tb-detail">
                            <tr>
                                <td colspan="3"><u><b>B. DEDUCTION</b></u></td>
                            </tr>
                            <tr>
                                <td>Pinjaman</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->pinjaman, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>BPJS</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->bpjs, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Jamsostek</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->jamsostek, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Union</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->union, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Absent</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->absent, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Electricity</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->electricity, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Cooperative</td>
                                <td>:</td>
                                <td class="text-end">
                                    {{ number_format($data['sal']->cooperative, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td>:</td>
                                <td class="text-end">{{ number_format($data['sal']->other, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="top-border">
                                <td><b>Sub Total</b></td>
                                <td>:</td>
                                <td class="text-end"><b>
                                        {{ number_format($data['sal']->total_deduction, 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td rowspan="2" style="vertical-align: top; padding-left:10px; padding-bottom: 0;">
                        <table class="tb-detail">
                            <tr>
                                <td colspan="3"><u><b>C. BENEFIT</b></u></td>
                            </tr>
                            <tr>
                                <td>Jamsostek JKK</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.0054, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Jamsostek JKM</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.003, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Jamsostek JHT</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.037, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Tax PPh 21</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['sal']->pph21_ben, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr class="top-border">
                                <td><b>Sub Total</b></td>
                                <td>:</td>
                                <td class="text-end"><b>
                                        {{ number_format($data['sal']->total_ben, 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                        <table class="tb-detail" style="margin-top: 28px;">
                            <tr>
                                <td colspan="3"><u><b>D. DEDUCTION BENEFIT</b></u></td>
                            </tr>
                            <tr>
                                <td>Jamsostek JKK</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.0054, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Jamsostek JKM</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.003, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Jamsostek JHT</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['total'] * 0.037, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td>Tax PPh 21</td>
                                <td>:</td>
                                {{-- <td class="text-end">{{ number_format($data['sal']->pph21_deb, 0, ',', '.') }}
                                </td> --}}
                                <td class="text-end">0</td>
                            </tr>
                            <tr class="top-border">
                                <td><b>Sub Total</b></td>
                                <td><b>:</b></td>
                                <td class="text-end"><b>
                                        {{ number_format($data['sal']->total_ben_ded, 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

                <tr>

                    <td style="padding-left: 10px; padding-right: 10px; margin-bottom: 0;">
                        <div class="outline-border" style="padding-left: 10px; padding-right: 10px; margin-top: -20;">
                            <table class="tb-detail">
                                <tr>
                                    <td>Salary Gross + <br>Total Benefit</td>
                                    <td>:</td>
                                    <td class="text-end">
                                        {{ number_format($data['sal']->gross_salary, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Deduction</td>
                                    <td>:</td>
                                    <td class="text-end">
                                        {{ number_format($data['sal']->total_deduction, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="top-border">
                                    <td><b>Nett Salary</b></td>
                                    <td>:</td>
                                    <td class="text-end"><b>
                                            {{ number_format($data['sal']->net_salary, 0, ',', '.') }}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>

                </tr>
            </table>

            <hr class="dash-line" style="margin-top: -15px">

            <div class="table-collapse">
                <table>
                    <tr>
                        <td>
                            Receive by
                            <br><br><br>
                            {{ $data['sal']->Nama }}
                        </td>
                        <td style="vertical-align: top">generate by system - no signature is required</td>
                    </tr>
                </table>
            </div>
        </div>
        @if ($loop->iteration % 2 == 0)
            <div class="page-break"></div>
        @endif
        <hr class="dash-line" style="margin-top: 0px; background-color: #022b83;border: none;">
    @endforeach
</body>

</html>
