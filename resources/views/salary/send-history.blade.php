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
                            <h3 class="text-white text-capitalize ps-3">{{ $title }}</h3>
                        </div>
                    </div>

                    <form action="{{ route('send-whatsapp-checked') }}" method="POST">
                        @csrf
                        <div class="card-body p-3 pb-2">
                            <div class="row">
                                {{-- <button data-bs-toggle="modal" data-bs-target="#sendData"
                                        class="btn btn-icon btn-3 btn-success btn-sm">
                                        <span class="btn-inner--icon"><i class="material-icons">share</i></span>
                                        <span class="btn-inner--text">Send Salary Slip</span>
                                    </button> --}}
                                <div class="col-2">
                                    <select class="form-select form-select-sm" name="filter_month">
                                        <option selected disabled>Select Month</option>
                                        @foreach ($months_filter as $month)
                                            <option value="{{ $month['value'] }}"
                                                {{ $selectedMonth == $month['value'] ? 'selected' : '' }}>
                                                {{ $month['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-icon btn-3 btn-primary btn-sm">
                                        <span class="btn-inner--icon"><i class="material-icons">mail</i></span>
                                        <span class="btn-inner--text">Send Selected</span>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive p-0">
                                <table
                                    class="table table-sm table-striped table-hover dtTable1 align-items-center small-tbl compact"
                                    id="example">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th rowspan="2" class="text-center"
                                                style="background-color: #1A73E8;color: white; p-0">
                                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">No</th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">NIK
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">Name
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">Dept
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">Job
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">Status
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">No HP
                                            </th>
                                            <th style="background-color: #1A73E8; color: white;" class="text-center">Year
                                            </th>
                                            @foreach ($months as $month)
                                                <th style="background-color: #1A73E8; color: white;" class="text-center">
                                                    {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $nik => $userData)
                                            @php
                                                $user = $userData->first();
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="salary_ids[]"
                                                        value="{{ $user->salary_year_id }}" class="selectItem"
                                                        onclick="togglePrintButton()">
                                                </td>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $user->nik }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->dept }}</td>
                                                <td>{{ $user->jabatan }}</td>
                                                <td>{{ $user->status }}</td>
                                                <td class="text-center">{{ $user->no_telpon }}</td>
                                                <td class="text-center">{{ $user->year }}</td>
                                                @foreach ($months as $month)
                                                    @php
                                                        $monthData = $userData->firstWhere('month', $month);
                                                    @endphp
                                                    <td class="text-center">
                                                        @if ($monthData)
                                                            @if ($monthData->is_send)
                                                                <span class="sent">✓</span>
                                                            @else
                                                                <span class="not-sent">✗</span>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="sendData" tabindex="-1" role="dialog" aria-labelledby="modal-form"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form action="{{ route('send-whatsapp-batch') }}" method="POST">
                            <div class="card card-plain">
                                <div class="modal-header">
                                    <h5 class="modal-title">Choose Date</h5>
                                </div>
                                <div class="card-body py-3">
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <label for="year" class="col pt-1">Year:</label>
                                                <select name="year" id="year"
                                                    class="col form-select form-select-sm">
                                                    <option selected disabled>-- Year --</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <label for="month" class="col pt-1">Month:</label>
                                                <select name="month" id="month"
                                                    class="col form-select form-select-sm">
                                                    <option selected disabled>-- Month --</option>
                                                    @foreach ($months_filter as $month)
                                                        <option value="{{ $month['value'] }}">
                                                            {{ $month['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <label for="status" class="col pt-1">Status:</label>
                                                <select name="filter_status" id="status"
                                                    class="col form-select form-select-sm">
                                                    <option selected disabled>-- Status --</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status }}">{{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success btn-sm"><span
                                            class="btn-inner--icon"><i class="material-icons">share</i></span>
                                        <span class="btn-inner--text">Send</span></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-3"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/tableToExcel.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
        <script>
            function toggleSelectAll(source) {
                checkboxes = document.querySelectorAll('.selectItem');
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = source.checked;
                });
                togglePrintButton();
            }

            function togglePrintButton() {
                const checkboxes = document.querySelectorAll('.selectItem:checked');
                const printButton = document.getElementById('printButton');
                printButton.disabled = checkboxes.length === 0;
            }
        </script>
        <script>
            $("#btn-d").click(function() {
                TableToExcel.convert(document.getElementById("example"), {
                    name: "All Salary Data.xlsx",
                });
            });
        </script>
    </div>
@endsection
