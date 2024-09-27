<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\SalaryGrade;
use App\Models\Status;
use App\Models\User;
use App\Models\SalaryYear;
use App\Models\SalaryMonth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class SalaryYearController extends Controller
{
    public function index()
    {
        $title = 'Salary Per Year';

        $data = DB::table('salary_years')
            ->join('users', 'salary_years.nik', '=', 'users.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select('users.nik', 'users.name', 'users.status', 'users.dept', 'users.jabatan', 'grade.name_grade',
                    'grade.rate_salary', 'salary_years.*', 'salary_years.id as salary_year_id')
            ->where('users.active', 'yes')
            ->get();

        $years = SalaryYear::distinct('year')->pluck('year')->toArray();
        $statuses = User::distinct('status')->pluck('status')->toArray();

        $selectedYearInput = request()->input('filter_year', '');
        $selectedStatus = request()->input('filter_status', '');

        $selectedYear = (int) $selectedYearInput;

        if ($selectedYear == null && $selectedStatus == null) {
            $data = DB::table('salary_years')
                ->join('users', 'salary_years.nik', '=', 'users.nik')
                ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
                ->select('users.nik', 'users.name', 'users.status', 'users.dept', 'users.jabatan', 'grade.name_grade',
                        'grade.rate_salary', 'salary_years.*', 'salary_years.id as salary_year_id')
                ->where('users.active', 'yes')
                ->get();

        } else {
            if ($selectedStatus == 'All Status') {
                $data = DB::table('salary_years')
                    ->join('users', 'salary_years.nik', '=', 'users.nik')
                    ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
                    ->select('users.nik', 'users.name', 'users.status', 'users.dept', 'users.jabatan', 'grade.name_grade',
                            'grade.rate_salary', 'salary_years.*', 'salary_years.id as salary_year_id')
                    ->whereYear('salary_years.year', $selectedYear)
                    ->where('users.active', 'yes')
                    ->get();
            } else {
                $data = DB::table('salary_years')
                    ->join('users', 'salary_years.nik', '=', 'users.nik')
                    ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
                    ->select('users.nik', 'users.name', 'users.status', 'users.dept', 'users.jabatan', 'grade.name_grade',
                            'grade.rate_salary', 'salary_years.*', 'salary_years.id as salary_year_id')
                    ->where('users.status', $selectedStatus)
                    ->whereYear('salary_years.year', $selectedYear)
                    ->where('users.active', 'yes')
                    ->get();
            }
        }

        $totalAbility = $data->sum('ability');
        $totalFungtionalAlw = $data->sum('fungtional_alw');
        $totalFamilyAlw = $data->sum('family_alw');
        $totalTransportAlw = $data->sum('transport_alw');
        $totalTelephoneAlw = $data->sum('telephone_alw');
        $totalSkillAlw = $data->sum('skill_alw');
        $totalAdjustment = $data->sum('adjustment');
        $totalBpjs = $data->sum('bpjs');
        $totalJamsostek = $data->sum('jamsostek');
        $totalRateSalary = $data->sum('rate_salary');

        return view('salary_year.index', compact(
            'title', 'years', 'statuses', 'selectedYear', 'selectedStatus', 'totalFamilyAlw', 'totalAbility', 'totalFungtionalAlw',
            'totalTransportAlw', 'totalTelephoneAlw', 'totalSkillAlw', 'totalAdjustment', 'totalBpjs', 'totalJamsostek', 'totalRateSalary', 'data',
        ));
    }

    public function filter(){
        $title = 'Salary Per Year';
        $statuses = User::select('status')->groupBy('status')->pluck('status');
        $currentYear = date('Y');

        return view('salary_year.filter', compact('title', 'statuses'));
    }

    public function filter_new() {
        $title = 'Salary Per Year';
        $statuses = Status::all();
        $currentYear = date('Y');
        // $currentYear = '2025';

        $allowedStatusNames = ['Assistant trainee', 'Manager', 'Monthly', 'Staff'];
        $selectedStatus = request()->input('id_status');

        $selectedStatusIds = $selectedStatus
            ? Status::whereIn('name_status', $allowedStatusNames)->where('id', $selectedStatus)->pluck('id')
            : Status::whereIn('name_status', $allowedStatusNames)->pluck('id');

        return view('salary_year.filter_new', compact('title', 'statuses', 'selectedStatus'));
    }

    public function create()
    {
        $title = 'Salary Per Year';
        $statuses = User::select('status')->groupBy('status')->pluck('status');
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentYear = date('Y');

        $checkYear = SalaryYear::where('year', $currentYear)->first();
        $allowedStatusNames = ['Manager', 'Monthly', 'Staff', 'Regular', 'Contract BSKP', 'Contract FL'];
        $selectedStatus = request()->input('status');

        $checkStatus = DB::table('salary_years')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->where('users.status', $selectedStatus)
            ->first();

        // dd($checkStatus, $checkYear, $selectedStatus);

        if ($checkStatus != null) {
            if ($checkYear) {

                $global = DB::table('users')
                    ->join('salary_years', 'salary_years.nik', '=', 'users.nik')
                    ->where('users.status', $selectedStatus)
                    ->pluck('users.nik');

                $remainingUsers = DB::table('users')
                    ->join('grade', 'users.grade', 'grade.name_grade')
                    ->select('users.nik as user_id', 'grade.id as salary_grade_id')
                    ->whereNotIn('users.nik', $global)
                    ->where('users.status', $selectedStatus)
                    ->where('users.active', 'yes')
                    ->get();

                    foreach ($remainingUsers as $g) {
                        // SalaryYear::create([
                        //     'nik' => $g->user_id,
                        //     'id_salary_grade' => $g->salary_grade_id,
                        //     'date' => $currentDate,
                        //     'year' => $currentYear,
                        //     'used' => '1'
                        // ]);

                        SalaryYear::firstOrCreate(
                            [
                                'nik' => $g->user_id,
                                'year' => $currentYear
                            ],
                            [
                                'id_salary_grade' => $g->salary_grade_id,
                                'date' => $currentDate,
                                'used' => '1'
                            ]
                        );
                    }

                $users = DB::table('users')
                    ->join('grade', 'users.grade', '=', 'grade.name_grade')
                    ->join('salary_years', 'salary_years.nik', '=', 'users.nik')
                    ->where('users.status', $selectedStatus)
                    ->where('salary_years.year', $currentYear)
                    ->where('users.active', 'yes')
                    // ->where(function ($query) {
                    //     $query->where('salary_years.ability', 0)
                    //         ->orWhere('salary_years.fungtional_alw', 0)
                    //         ->orWhere('salary_years.family_alw', 0)
                    //         ->orWhere('salary_years.transport_alw', 0)
                    //         ->orWhere('salary_years.telephone_alw', 0)
                    //         ->orWhere('salary_years.skill_alw', 0)
                    //         ->orWhere('salary_years.adjustment', 0)
                    //         ->orWhere('salary_years.bpjs', 0)
                    //         ->orWhere('salary_years.jamsostek', 0);
                    // })
                    ->select('users.*', 'salary_years.*', 'grade.*', 'users.nik as id_user', 'grade.id as id_grade')
                    ->get();
            } else {
                $users = DB::table('users')
                    ->join('grade', 'users.grade', '=', 'grade.name_grade')
                    ->join('salary_years', 'salary_years.nik', '=', 'users.nik')
                    ->where('users.status', $selectedStatus)
                    ->where('users.active', 'yes')
                    ->select('users.*', 'grade.*', 'users.nik as id_user')
                    ->get();
            }
        } else {
            $users = DB::table('users')
                ->join('grade', 'users.grade', '=', 'grade.name_grade')
                ->where('users.status', $selectedStatus)
                ->where('users.active', 'yes')
                ->select('users.*', 'grade.*', 'users.nik as id_user', 'grade.id as id_grade')
                ->get();
        }

        return view('salary_year.create', compact('title', 'users', 'statuses', 'selectedStatus', 'currentYear'));
    }

    public function store(Request $request)
    {
        foreach ($request->input('nik') as $key => $value) {

            $input = $request->only([
                'nik', 'id_salary_grade', 'rate_salary',
                'ability', 'fungtional_alw', 'family_alw',
                'transport_alw', 'telephone_alw', 'skill_alw', 'adjustment'
            ]);

            $rate_salary = isset($input['rate_salary'][$key]) ? (int) str_replace(',', '', $input['rate_salary'][$key]) : 0;
            $ability = isset($input['ability'][$key]) ? (int) str_replace(',', '', $input['ability'][$key]) : 0;
            $fungtional_alw = isset($input['fungtional_alw'][$key]) ? (int) str_replace(',', '', $input['fungtional_alw'][$key]) : 0;
            $family_alw = isset($input['family_alw'][$key]) ? (int) str_replace(',', '', $input['family_alw'][$key]) : 0;
            $transport_alw = isset($input['transport_alw'][$key]) ? (int) str_replace(',', '', $input['transport_alw'][$key]) : 0;
            $telephone_alw = isset($input['telephone_alw'][$key]) ? (int) str_replace(',', '', $input['telephone_alw'][$key]) : 0;
            $skill_alw = isset($input['skill_alw'][$key]) ? (int) str_replace(',', '', $input['skill_alw'][$key]) : 0;
            $adjustment = isset($input['adjustment'][$key]) ? (int) str_replace(',', '', $input['adjustment'][$key]) : 0;

            // $total = $rate_salary + $ability + $family_alw;
            $totalBpjs = $rate_salary + $ability + $family_alw + $fungtional_alw;
            $totalJamsostek = $rate_salary + $ability + $family_alw + $skill_alw + $fungtional_alw;

            if ($totalBpjs > 12000000) {
                $bpjs = 12000000 * 0.01;
            } else {
                $bpjs = $totalBpjs * 0.01;
            }
            $jamsostek = $totalJamsostek * 0.02;

            $jamsostek_jkk = $totalJamsostek * 0.0054;
            $jamsostek_tk = $totalJamsostek * 0.003;
            $jamsostek_tht = $totalJamsostek * 0.037;
            $total_jamsostek = $jamsostek_jkk + $jamsostek_tk + $jamsostek_tht;

            $allocations = $request->input('allocation')[$key] ?? NULL;
            if ($allocations) {
                $allocationJson = json_encode($allocations);
            } else {
                $allocationJson = $allocations;
            }

            SalaryYear::updateOrCreate(
                [
                    'nik' => $input['nik'][$key],
                    'year' => date('Y'),
                ],
                [
                    'id_salary_grade' => $input['id_salary_grade'][$key],
                    'rate_salary' => $rate_salary,
                    'ability' => $ability,
                    'fungtional_alw' => $fungtional_alw,
                    'family_alw' => $family_alw,
                    'transport_alw' => $transport_alw,
                    'telephone_alw' => $telephone_alw,
                    'skill_alw' => $skill_alw,
                    'adjustment' => $adjustment,
                    'bpjs' => $bpjs,
                    'jamsostek' => $jamsostek,
                    'total_ben' => $total_jamsostek,
                    'total_ben_ded' => $total_jamsostek,
                    'allocation' => $allocationJson,
                ]
            );
        }

        return redirect()->route('salary-year')->with('success', 'Data gaji berhasil disimpan.');
    }

    public function edit(Request $request)
    {
        $grade = Grade::all();
        $selectedIds = $request->input('ids', '');

        if (is_string($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
        }

        if (empty($selectedIds)) {
            return redirect()->route('salary-year')->with('error', 'No data selected for editing.');
        }

        $title = 'Salary Per Grade';

        $salary_years = DB::table('salary_years')
            ->join('users', 'salary_years.nik', '=', 'users.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select(
                'users.nik',
                'users.name',
                'users.status',
                'users.dept',
                'users.jabatan',
                'users.grade',
                'grade.rate_salary',
                'grade.name_grade',
                'salary_years.id_salary_grade',
                'salary_years.ability',
                'salary_years.fungtional_alw',
                'salary_years.family_alw',
                'salary_years.transport_alw',
                'salary_years.telephone_alw',
                'salary_years.skill_alw',
                'salary_years.adjustment',
                'salary_years.year',
                'salary_years.allocation',
                'salary_years.id as salary_years_id',
            )
            ->whereIn('salary_years.id', $selectedIds)
            ->get();

        $currentYear = date('Y');

        return view('salary_year.edit', compact('title', 'salary_years', 'grade'));
    }

    public function update(Request $request)
    {
        // dd($request->input('id_grade'));
        foreach ($request->input('ids') as $id) {

            $input = $request->only([
                'id_grade', 'rate_salary', 'ability', 'fungtional_alw', 'family_alw',
                'transport_alw', 'telephone_alw', 'skill_alw', 'adjustment', 'id_user'
            ]);

            // $id_grade = $request->has('id_grade.' . $id) ? (int) str_replace(',', '', $request->input('id_grade.' . $id)) : 0;
            // $rate_salary = $request->has('rate_salary.' . $id) ? (int) str_replace(',', '', $request->input('rate_salary.' . $id)) : 0;

            $id_user = $input['id_user'][$id] ?? 0;
            $id_grade = $input['id_grade'][$id] ?? 0;
            $rate_salary = Grade::where('id', $id_grade)->value('rate_salary');
            $name_grade = Grade::where('id', $id_grade)->value('name_grade');
            $ability = $request->has('ability.' . $id) ? (int) str_replace(',', '', $request->input('ability.' . $id)) : 0;
            $fungtional_alw = $request->has('fungtional_alw.' . $id) ? (int) str_replace(',', '', $request->input('fungtional_alw.' . $id)) : 0;
            $family_alw = $request->has('family_alw.' . $id) ? (int) str_replace(',', '', $request->input('family_alw.' . $id)) : 0;
            $transport_alw = $request->has('transport_alw.' . $id) ? (int) str_replace(',', '', $request->input('transport_alw.' . $id)) : 0;
            $telephone_alw = $request->has('telephone_alw.' . $id) ? (int) str_replace(',', '', $request->input('telephone_alw.' . $id)) : 0;
            $skill_alw = $request->has('skill_alw.' . $id) ? (int) str_replace(',', '', $request->input('skill_alw.' . $id)) : 0;
            $adjustment = $request->has('adjustment.' . $id) ? (int) str_replace(',', '', $request->input('adjustment.' . $id)) : 0;

            $totalBpjs = $rate_salary + $ability + $family_alw + $fungtional_alw;
            $totalJamsostek = $rate_salary + $ability + $family_alw + $skill_alw + $fungtional_alw;

            if ($totalBpjs > 12000000) {
                $bpjs = 12000000 * 0.01;
            } else {
                $bpjs = $totalBpjs * 0.01;
            }

            // $total = $rate_salary + $ability + $fungtional_alw + $family_alw + $transport_alw + $telephone_alw + $skill_alw;
            // if ($total2 > 12000000) {
            //     $bpjs2 = 12000000 * 0.01;
            // } else {
            //     $bpjs2 = $total2 * 0.01;
            // }

            // dd($total, $total2, $bpjs, $bpjs2);


            $jamsostek = $totalJamsostek * 0.02;

            $jamsostek_jkk = $totalJamsostek * 0.0054;
            $jamsostek_tk = $totalJamsostek * 0.003;
            $jamsostek_tht = $totalJamsostek * 0.037;
            $total_jamsostek = $jamsostek_jkk + $jamsostek_tk + $jamsostek_tht;

            $allocations = $request->input('allocations')[$id] ?? NULL;
            if ($allocations) {
                $allocationJson = json_encode($allocations);
            } else {
                $allocationJson = $allocations;
            }

            $updateSalaryYear = SalaryYear::where('id', $id)->update([
                'id_salary_grade' => $id_grade,
                'ability' => $ability,
                'fungtional_alw' => $fungtional_alw,
                'family_alw' => $family_alw,
                'transport_alw' => $transport_alw,
                'telephone_alw' => $telephone_alw,
                'skill_alw' => $skill_alw,
                'adjustment' => $adjustment,
                'bpjs' => $bpjs,
                'jamsostek' => $jamsostek,
                'total_ben' => $total_jamsostek,
                'total_ben_ded' => $total_jamsostek,
                'allocation' => $allocationJson,
            ]);

            if ($updateSalaryYear) {
                User::where('nik', $id_user)->update([
                    'grade' => $name_grade
                ]);
            }

        }

        return redirect()->route('salary-year')->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function show()
    {

    }

    public function get_emp(Request $request)
    {
        $emp = DB::table('users')
            ->join('grades', 'users.id_grade', '=', 'grades.id')
            ->where('id_status', $request->id_status)
            ->select('users.id as users_id', 'users.nik', 'users.name', 'grades.name_grade')
            ->get();

        // $emp = User::where('id_status', $request->id_status)->select('id', 'nik', 'name')->get();
        return response()->json($emp);
    }

    public function get_rate_salary(Request $request)
    {
        // $rate_salary = SalaryGrade::where('id_grade', $request->id_grade)->select('id', 'rate_salary')->get();
        $rate_salary = Grade::where('id', $request->id_grade)->select('id', 'rate_salary')->get();
        return response()->json($rate_salary);
    }

    public function create_new(Request $request)
    {
        $title = 'Salary Per Year';
        $grade = Grade::all();
        $ids = $request->input('id');

        if (is_array($ids) && !empty($ids)) {

            $salary_years = DB::table('salary_years')
                ->join('salary_grades', 'salary_years.id_salary_grade', '=', 'salary_grades.id')
                ->join('grades', 'salary_grades.id_grade', '=', 'grades.id')
                ->join('users', 'salary_years.id_user', '=', 'users.id')
                ->join('statuses', 'users.id_status', '=', 'statuses.id')
                ->join('jobs', 'users.id_job', '=', 'jobs.id')
                ->join('depts', 'users.id_dept', '=', 'depts.id')
                ->select('salary_years.*', 'salary_grades.*', 'grades.*', 'users.*', 'statuses.*', 'jobs.*', 'depts.*',
                        'users.id as user_id', 'salary_years.id as salary_years_id', 'salary_grades.id as salary_grades_id', 'grades.id as grades_id')
                ->whereIn('salary_years.id_user', $ids)
                ->get();

            return view('salary_year.create_new', [
                'title' => $title,
                'salary_years' => $salary_years,
                'grade' => $grade
            ]);

        } else {
            return redirect()->back()->with('error', 'No users selected.');
        }
    }

    public function store_new(Request $request)
    {
        foreach ($request->input('id_user') as $key => $value) {

            $input = $request->only([
                'id_user', 'id_salary_grade', 'rate_salary',
                'ability', 'fungtional_alw', 'family_alw',
                'transport_alw', 'telephone_alw', 'skill_alw',
                'adjustment', 'date', 'id_grade'
            ]);

            // $rate_salary = isset($input['rate_salary'][$key]) ? (int) str_replace(',', '', $input['rate_salary'][$key]) : 0;
            $id_grade = $input['id_grade'][$key] ?? 0;
            $rate_salary = SalaryGrade::where('id_grade', $id_grade)->value('rate_salary');
            $ability = isset($input['ability'][$key]) ? (int) str_replace(',', '', $input['ability'][$key]) : 0;
            $fungtional_alw = isset($input['fungtional_alw'][$key]) ? (int) str_replace(',', '', $input['fungtional_alw'][$key]) : 0;
            $family_alw = isset($input['family_alw'][$key]) ? (int) str_replace(',', '', $input['family_alw'][$key]) : 0;
            $transport_alw = isset($input['transport_alw'][$key]) ? (int) str_replace(',', '', $input['transport_alw'][$key]) : 0;
            $telephone_alw = isset($input['telephone_alw'][$key]) ? (int) str_replace(',', '', $input['telephone_alw'][$key]) : 0;
            $skill_alw = isset($input['skill_alw'][$key]) ? (int) str_replace(',', '', $input['skill_alw'][$key]) : 0;
            $adjustment = isset($input['adjustment'][$key]) ? (int) str_replace(',', '', $input['adjustment'][$key]) : 0;

            $total = $rate_salary + $ability + $family_alw;

            if ($total > 12000000) {
                $bpjs = 12000000 * 0.01;
            } else {
                $bpjs = $total * 0.01;
            }
            $jamsostek = $total * 0.02;

            $jamsostek_jkk = $total * 0.0054;
            $jamsostek_tk = $total * 0.003;
            $jamsostek_tht = $total * 0.037;
            $total_jamsostek = $jamsostek_jkk + $jamsostek_tk + $jamsostek_tht;

            $allocations = $request->input('allocation')[$key] ?? NULL;
            if ($allocations) {
                $allocationJson = json_encode($allocations);
            } else {
                $allocationJson = $allocations;
            }

            $addNew = SalaryYear::where('id', $request->input('ids')[$key])
                    ->update([
                        'used' => '0'
                    ]);

            $addNews = User::where('id', $request->input('id_user')[$key])
                    ->update([
                        'id_grade' => $id_grade
                    ]);

            if ($addNew && $addNews) {
                SalaryYear::create([
                    'id_user' => $input['id_user'][$key],
                    'id_salary_grade' => $input['id_salary_grade'][$key],
                    'date' => $request->input('date'),
                    'year' => date('Y'),
                    'ability' => $ability,
                    'fungtional_alw' => $fungtional_alw,
                    'family_alw' => $family_alw,
                    'transport_alw' => $transport_alw,
                    'telephone_alw' => $telephone_alw,
                    'skill_alw' => $skill_alw,
                    'adjustment' => $adjustment,
                    'bpjs' => $bpjs,
                    'jamsostek' => $jamsostek,
                    'total_ben' => $total_jamsostek,
                    'total_ben_ded' => $total_jamsostek,
                    'allocation' => $allocationJson,
                    'used' => '1'
                ]);
            }

            // SalaryYear::updateOrCreate(
            //     [
            //         'id_user' => $input['id_user'][$key],
            //         'year' => date('Y'),
            //     ],
            //     [
            //         'id_salary_grade' => $input['id_salary_grade'][$key],
            //         'rate_salary' => $rate_salary,
            //         'ability' => $ability,
            //         'fungtional_alw' => $fungtional_alw,
            //         'family_alw' => $family_alw,
            //         'transport_alw' => $transport_alw,
            //         'telephone_alw' => $telephone_alw,
            //         'skill_alw' => $skill_alw,
            //         'adjustment' => $adjustment,
            //         'bpjs' => $bpjs,
            //         'jamsostek' => $jamsostek,
            //         'total_ben' => $total_jamsostek,
            //         'total_ben_ded' => $total_jamsostek,
            //         'allocation' => $allocationJson,
            //     ]
            // );
        }

        return redirect()->route('salary-year')->with('success', 'Data gaji berhasil disimpan.');
    }
}
