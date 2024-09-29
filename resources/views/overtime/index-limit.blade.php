@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">All Salary Data</h6>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <div class="row">
                            <div class="col-5 justify-content-end">
                                <form action="{{ url('/overtime-limit-index') }}" method="GET">
                                    <div class="row">
                                        <div class="col pe-0">
                                            <select class="form-select form-select-sm" name="filter_status">
                                                <option selected disabled>-- Pilih Status --</option>
                                                <option value="All Status">All Status</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}">{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="col-7">
                            </div> --}}
                        </div>
                        <div class="table-responsive p-0">
                            <form method="POST" action="{{ route('overtime-limit-store') }}">
                                @csrf
                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                <table
                                    class="table table-sm table-striped table-hover dtTable4 align-items-center small-tbl compact"
                                    id="example">
                                    <thead class="bg-thead">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th colspan="6" class="text-center p-0">Employee Identity</th>
                                            <th rowspan="2" class="text-center">Approve</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #1A73E8;color: white;">Emp Code</th>
                                            <th style="background-color: #1A73E8;color: white;">Name</th>
                                            <th>Status</th>
                                            <th>Dept</th>
                                            <th>Job</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $index => $emp)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $emp->nik }}</td>
                                                <td>{{ $emp->name }}</td>
                                                <td>{{ $emp->status }}</td>
                                                <td>{{ $emp->dept }}</td>
                                                <td>{{ $emp->jabatan }}</td>
                                                <td>{{ $emp->grade }}</td>
                                                <td>
                                                    <input type="hidden" name="nik[]" value="{{ $emp->nik }}">
                                                    <div class="input-group input-group-outline">
                                                        <input type="number" class="form-control form-control-sm"
                                                            name="overtime_limit[]"
                                                            value="{{ $emp->overtime_limit ?? '0' }}">
                                                    </div>
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
    @endsection
