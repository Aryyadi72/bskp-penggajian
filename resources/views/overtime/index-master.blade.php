@extends('layouts.main')
@section('content')
    {{-- Bagian  Isi Konten --}}
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Overtime Matrix Data</h6>
                    </div>
                </div>

                <div class="card-body p-3 pb-2">
                    <button data-bs-toggle="modal" data-bs-target="#addOvertimeMatrix" class="btn btn-info btn-sm">Add
                        Data</button>
                    <div class="table-responsive p-0">
                        <table class="table table-small table-striped table-hover dtTable align-items-center compact">
                            <thead>
                                <tr>
                                    <th style="background-color: #1A73E8;color: white;">No</th>
                                    <th style="background-color: #1A73E8;color: white;">Minimum Minute</th>
                                    <th style="background-color: #1A73E8;color: white;">Maximum Minute</th>
                                    <th style="background-color: #1A73E8;color: white;">Minute Value</th>
                                    <th style="background-color: #1A73E8;color: white;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $matriks)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $matriks->overtime_min }}m</td>
                                        <td class="text-center">{{ $matriks->overtime_max }}m</td>
                                        <td class="text-center">{{ $matriks->overtime_value }}j</td>
                                        <td class="text-center m-0 p-0">
                                            <button class="btn btn-warning btn-icon-only m-0 p-0 btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editOvertimeMatrix{{ $matriks->id }}" title="Edit Data">
                                                <span class="btn-inner--icon"><i class="material-icons">edit</i></span>
                                            </button>
                                            <button class="btn btn-danger btn-icon-only m-0 p-0 btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteOvertimeMatrix{{ $matriks->id }}"
                                                title="Delete Data">
                                                <span class="btn-inner--icon"><i class="material-icons">delete</i></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /Bagian  Isi Konten --}}

    {{-- MODAL --}}
    {{-- Modal Add --}}
    <div class="modal fade" id="addOvertimeMatrix" tabindex="-1" role="dialog" aria-labelledby="modal-form"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Overtime Matrix Data</h5>
                        </div>

                        <form action="{{ route('overtime-master-store') }}" method="post" class="add_edit_grade">
                            @csrf
                            <div class="card-body py-0">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Overtime Min (Minute)</label>
                                    <input type="number" class="form-control" name="overtime_min">
                                </div>
                            </div>
                            <div class="card-body py-0">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Overtime Max (Minute)</label>
                                    <input type="number" class="form-control" name="overtime_max">
                                </div>
                            </div>
                            <div class="card-body py-0">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Overtime Value</label>
                                    <input type="number" class="form-control" name="overtime_value">
                                </div>
                            </div>
                            <div class="card-footer text-end pt-0">
                                <button type="button" class="btn btn-sm  btn-outline-secondary m-0" data-bs-dismiss="modal"
                                    onclick="resetForm('add_edit_grade')">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-success text-white m-0">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($data as $matrix)
        {{-- Modal Edit --}}
        <div class="modal fade" id="editOvertimeMatrix{{ $matrix->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Grade Data</h5>
                            </div>

                            <form action="{{ route('overtime-master-update', $matrix->id) }}" method="post"
                                class="add_edit_grade">
                                @csrf {{-- Cross-Site Request Forgery --}}
                                @method('PUT'){{-- Method Override merupakan directive Blade di Laravel --}}

                                <div class="card-body py-0">
                                    <div class="input-group input-group-outline my-3 is-focused">
                                        <label class="form-label">Overtime Min (Minute)</label>
                                        <input type="number" class="form-control" name="overtime_min"
                                            value="{{ $matrix->overtime_min }}">
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="input-group input-group-outline my-3 is-focused">
                                        <label class="form-label">Overtime Max (Minute)</label>
                                        <input type="number" class="form-control" name="overtime_max"
                                            value="{{ $matrix->overtime_max }}">
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="input-group input-group-outline my-3 is-focused">
                                        <label class="form-label">Overtime Value</label>
                                        <input type="number" class="form-control" name="overtime_value"
                                            value="{{ $matrix->overtime_value }}">
                                    </div>
                                </div>

                                <div class="card-footer text-end pt-0">
                                    <button type="button" class="btn btn-sm  btn-outline-secondary m-0"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-success text-white m-0">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Delete --}}
        <div class="modal fade" id="deleteOvertimeMatrix{{ $matrix->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Grade Data</h5>
                            </div>
                            <form action="{{ route('overtime-master-destroy', $matrix->id) }}" method="post">
                                @csrf {{-- Cross-Site Request Forgery --}}
                                @method('DELETE')

                                <input type="hidden" class="form-control" name="id" value="{{ $matrix->id }}">

                                <p class="mx-3">Apakah anda yakin ingin menghapus data?</p>
                                <div class="card-footer text-end pt-0">
                                    <button type="button" class="btn btn-sm  btn-outline-danger m-0"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-danger text-white m-0">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- /MODAL --}}
@endsection
