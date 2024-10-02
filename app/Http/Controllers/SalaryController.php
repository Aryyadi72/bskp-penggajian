<?php

namespace App\Http\Controllers;

use App\Models\SalaryMonth;
use App\Models\Status;
use App\Models\SalaryYear;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Twilio\Rest\Client;
use WaAPI\WaAPI;
use Illuminate\Support\Str;
use App\Jobs\SendCheckedSalaryJob;

class SalaryController extends Controller
{
    public function index()
    {
        $title = 'Salary';

        // $data = DB::table('salary_months')
        //         ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
        //         ->join('users', 'users.nik', '=', 'salary_years.nik')
        //         ->join('grade', 'users.grade', '=', 'grade.name_grade')
        //         ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date','salary_months.id as salary_month_id')
        //         ->get();

        $salary_months = SalaryMonth::all();

        $years = SalaryMonth::distinct('date')->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();
        $months = SalaryMonth::distinct('date')->pluck('date')->map(function ($date) {
            $carbonDate = Carbon::parse($date);
            return [
                'value' => $carbonDate->format('m'),
                'label' => $carbonDate->format('F'),
            ];
        })->unique()->toArray();

        $statuses = User::distinct('status')->pluck('status')->toArray();

        $query = SalaryMonth::with('salary_year');

        $selectedYear = trim(request()->input('filter_year', ''));
        $selectedMonth = trim(request()->input('filter_month', ''));
        $selectedStatus = trim(request()->input('filter_status', ''));
        $selectedApprove = trim(request()->input('filter_approval', ''));

        // dd($selectedYear, $selectedMonth, $selectedStatus, $selectedApprove);

        $selectedYear = (int) $selectedYear;
        $selectedMonth = (int) $selectedMonth;

        if ($selectedYear == null && $selectedMonth == null && $selectedStatus == null && $selectedApprove == null) {
            $data = DB::table('salary_months')
                ->join('salary_years', 'salary_months.id_salary_year', '=', 'salary_years.id')
                ->join('users', 'users.nik', '=', 'salary_years.nik')
                ->join('grade', 'users.grade', '=', 'grade.name_grade')
                ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                ->whereIn('users.status', ['Manager', 'Staff', 'Monthly', 'Contract BSKP'])
                ->where('users.active', 'yes')
                // ->where('salary_years.nik', '200-151')
                ->get();
        } else {
            if ($selectedStatus == 'All Status') {
                if ($selectedApprove == 1) {
                    $data = DB::table('salary_months')
                        ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                        ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                        ->join('users', 'users.nik', '=', 'salary_years.nik')
                        ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                        ->whereYear('salary_months.date', $selectedYear)
                        ->whereMonth('salary_months.date', $selectedMonth)
                        ->where('salary_months.is_approved', 1)
                        ->where('users.active', 'yes')
                        ->get();
                } else {
                    $data = DB::table('salary_months')
                        ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                        ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                        ->join('users', 'users.nik', '=', 'salary_years.nik')
                        ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                        ->whereYear('salary_months.date', $selectedYear)
                        ->whereMonth('salary_months.date', $selectedMonth)
                        // ->where('salary_months.is_approved', 0)
                        ->where('users.active', 'yes')
                        ->get();
                }

            } else {
                if ($selectedApprove == 1) {
                    $data = DB::table('salary_months')
                        ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                        ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                        ->join('users', 'users.nik', '=', 'salary_years.nik')
                        ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                        ->where('users.status', $selectedStatus)
                        ->whereYear('salary_months.date', $selectedYear)
                        ->whereMonth('salary_months.date', $selectedMonth)
                        ->where('salary_months.is_approved', 1)
                        ->where('users.active', 'yes')
                        ->get();
                } else {
                    $data = DB::table('salary_months')
                        ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                        ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                        ->join('users', 'users.nik', '=', 'salary_years.nik')
                        ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                        ->where('users.status', $selectedStatus)
                        ->whereYear('salary_months.date', $selectedYear)
                        ->whereMonth('salary_months.date', $selectedMonth)
                        ->where('salary_months.is_approved', 0)
                        ->where('users.active', 'yes')
                        ->get();
                }
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

        $totalHourCall = $data->sum('hour_call');
        $totalTotalOT = $data->sum('total_overtime');
        $totalThr = $data->sum('thr');
        $totalBonus = $data->sum('bonus');
        $totalIncentive = $data->sum('incentive');
        $totalUnion = $data->sum('union');
        $totalAbsent = $data->sum('absent');
        $totalElectricity = $data->sum('electricity');
        $totalCooperative = $data->sum('cooperative');
        $totalPinjaman = $data->sum('pinjaman');
        $totalOther = $data->sum('other');
        $totalTotalded = $data->sum('total_deduction');
        $totalNetsalary = $data->sum('net_salary');

        $totalRateSalary = $data->sum(function ($data) {
            return $data->rate_salary;
        });

        return view('salary.index', compact(
            'title',
            'statuses',
            'years',
            'months',
            'salary_months',
            'selectedStatus',
            'selectedYear',
            'selectedMonth',
            'data',
            'totalFamilyAlw',
            'totalAbility',
            'totalFungtionalAlw',
            'totalTransportAlw',
            'totalTelephoneAlw',
            'totalSkillAlw',
            'totalAdjustment',
            'totalBpjs',
            'totalJamsostek',
            'totalRateSalary',
            'totalHourCall',
            'totalTotalOT',
            'totalThr',
            'totalBonus',
            'totalIncentive',
            'totalUnion',
            'totalAbsent',
            'totalElectricity',
            'totalCooperative',
            'totalPinjaman',
            'totalOther',
            'totalTotalded',
            'totalNetsalary',
            'selectedApprove'
        ));
    }

    public function salary_check(Request $request)
    {
        $selectedIds = $request->input('salary_ids');

        if ($selectedIds) {
            foreach ($selectedIds as $id) {
                DB::table('salary_months')
                    ->where('id', $id)
                    ->update([
                        'is_checked' => 1,
                    ]);
            }

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }
    }

    public function salary_approved(Request $request)
    {
        $selectedIds = $request->input('salary_ids');

        $data = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select(
                'salary_months.*',
                'grade.name_grade',
                'grade.rate_salary',
                'salary_years.nik',
                'salary_years.year',
                'salary_years.ability',
                'salary_years.fungtional_alw',
                'salary_years.family_alw',
                'salary_years.transport_alw',
                'salary_years.skill_alw',
                'salary_years.telephone_alw',
                'salary_years.adjustment',
                'salary_years.bpjs',
                'salary_years.jamsostek',
                'salary_years.total_ben',
                'salary_years.total_ben_ded'
            )
            ->whereIn('salary_months.id', $selectedIds)
            ->get();

        if ($data != null) {
            $approve = DB::table('salary_months')
                ->whereIn('id', $selectedIds)
                ->update([
                    'is_checked' => 1,
                    'is_approved' => 1
                ]);
        }

        if ($approve) {
            $created_at = Carbon::now();
            foreach ($data as $emp) {
                DB::table('all_salary_data')->insert([
                    'nik' => $emp->nik,
                    'salary_grade' => $emp->name_grade,
                    'rate_salary' => $emp->rate_salary,
                    'date' => $emp->date,
                    'year' => $emp->year,
                    'ability' => $emp->ability,
                    'fungtional_alw' => $emp->fungtional_alw,
                    'family_alw' => $emp->family_alw,
                    'transport_alw' => $emp->transport_alw,
                    'skill_alw' => $emp->skill_alw,
                    'telephone_alw' => $emp->telephone_alw,
                    'adjustment' => $emp->adjustment,
                    'bpjs' => $emp->bpjs,
                    'jamsostek' => $emp->jamsostek,
                    'total_ben' => $emp->total_ben,
                    'total_ben_ded' => $emp->total_ben_ded,
                    'total_overtime' => $emp->total_overtime,
                    'thr' => $emp->thr,
                    'bonus' => $emp->bonus,
                    'incentive' => $emp->incentive,
                    'union' => $emp->union,
                    'absent' => $emp->absent,
                    'electricity' => $emp->electricity,
                    'cooperative' => $emp->cooperative,
                    'pinjaman' => $emp->pinjaman,
                    'other' => $emp->other,
                    'gross_salary' => $emp->gross_salary,
                    'total_deduction' => $emp->total_deduction,
                    'net_salary' => $emp->net_salary,
                    'created_at' => $created_at,
                ]);
            }
            return redirect()->back()->with('success', 'Data berhasil disimpan ke database.');
        } else {
            return redirect()->back()->with('error', 'Data tidak berhasil disimpan ke database.');
        }
    }

    public function salary_print()
    {
        $title = 'Salary';

        $data = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->join('grade', 'users.grade', '=', 'grade.name_grade')
            ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
            ->get();

        $salary_months = SalaryMonth::all();

        $years = SalaryMonth::distinct('date')->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();
        $months = SalaryMonth::distinct('date')->pluck('date')->map(function ($date) {
            $carbonDate = Carbon::parse($date);
            return [
                'value' => $carbonDate->format('m'),
                'label' => $carbonDate->format('F'),
            ];
        })->unique()->toArray();

        $statuses = User::distinct('status')->pluck('status')->toArray();

        $query = SalaryMonth::with('salary_year');

        $selectedYear = trim(request()->input('filter_year', ''));
        $selectedMonth = trim(request()->input('filter_month', ''));
        $selectedStatus = trim(request()->input('filter_status', ''));

        $selectedYear = (int) $selectedYear;
        $selectedMonth = (int) $selectedMonth;

        if ($selectedYear == null && $selectedMonth == null && $selectedStatus == null) {
            $data = DB::table('salary_months')
                ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                ->join('users', 'users.nik', '=', 'salary_years.nik')
                ->join('grade', 'users.grade', '=', 'grade.name_grade')
                ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                ->whereIn('users.status', ['Manager', 'Staff', 'Monthly'])
                ->where('users.active', 'yes')
                ->get();
        } else {
            if ($selectedStatus == 'All Status') {
                $data = DB::table('salary_months')
                    ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                    ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                    ->join('users', 'users.nik', '=', 'salary_years.nik')
                    ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                    ->whereYear('salary_months.date', $selectedYear)
                    ->whereMonth('salary_months.date', $selectedMonth)
                    ->get();
            } else {
                $data = DB::table('salary_months')
                    ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
                    ->join('grade', 'grade.id', '=', 'salary_years.id_salary_grade')
                    ->join('users', 'users.nik', '=', 'salary_years.nik')
                    ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date', 'salary_months.id as salary_month_id')
                    ->where('users.status', $selectedStatus)
                    ->whereYear('salary_months.date', $selectedYear)
                    ->whereMonth('salary_months.date', $selectedMonth)
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

        $totalHourCall = $data->sum('hour_call');
        $totalTotalOT = $data->sum('total_overtime');
        $totalThr = $data->sum('thr');
        $totalBonus = $data->sum('bonus');
        $totalIncentive = $data->sum('incentive');
        $totalUnion = $data->sum('union');
        $totalAbsent = $data->sum('absent');
        $totalElectricity = $data->sum('electricity');
        $totalCooperative = $data->sum('cooperative');
        $totalPinjaman = $data->sum('pinjaman');
        $totalOther = $data->sum('other');
        $totalTotalded = $data->sum('total_deduction');
        $totalNetsalary = $data->sum('net_salary');

        $totalRateSalary = $data->sum(function ($data) {
            return $data->rate_salary;
        });

        return view('salary.print_index', compact(
            'title',
            'statuses',
            'years',
            'months',
            'salary_months',
            'selectedStatus',
            'selectedYear',
            'selectedMonth',
            'data',
            'totalFamilyAlw',
            'totalAbility',
            'totalFungtionalAlw',
            'totalTransportAlw',
            'totalTelephoneAlw',
            'totalSkillAlw',
            'totalAdjustment',
            'totalBpjs',
            'totalJamsostek',
            'totalRateSalary',
            'totalHourCall',
            'totalTotalOT',
            'totalThr',
            'totalBonus',
            'totalIncentive',
            'totalUnion',
            'totalAbsent',
            'totalElectricity',
            'totalCooperative',
            'totalPinjaman',
            'totalOther',
            'totalTotalded',
            'totalNetsalary',
        ));
    }

    public function print($id)
    {
        $sal = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->select(
                'users.nik as Emp_Code',
                'users.name as Nama',
                'users.status as Status',
                'users.dept as Dept',
                'users.jabatan as Jabatan',
                'users.start_work_user',
                'grade.name_grade as Grade',
                'grade.rate_salary',
                'salary_years.*',
                'salary_months.*',
                'salary_months.absent',
                'salary_months.electricity',
                'salary_months.cooperative',
                'salary_months.pinjaman',
                'salary_months.other',
                'salary_months.date as salary_months_date',
                'salary_months.total_deduction',
                'salary_months.net_salary'
            )
            ->where('salary_months.id', $id)
            ->first();


            $date = date('My', strtotime($sal->salary_months_date));

            // dd(vars: $sal->salary_month_date);

        if (!$sal) {
            dd("Salary with ID $id not found.");
        }

        $rate_salary = $sal->rate_salary;
        $ability = $sal->ability;
        $fungtional_alw = $sal->fungtional_alw;
        $family_alw = $sal->family_alw;

        $total = $rate_salary + $ability + $fungtional_alw + $family_alw;

        $pdf = PDF::loadView('salary.print', compact('sal', 'total'));
        return $pdf->setPaper('a5', 'landscape')->stream('SAL_' . $date . '_' . $sal->Emp_Code . '_' . $sal->Nama . '.pdf');
    }

    public function printMultiple(Request $request)
    {
        $salaryIds = $request->input('salary_ids');

        if (empty($salaryIds)) {
            return back()->with('error', 'Please select at least one salary to print.');
        }

        $salaries = SalaryMonth::whereIn('id', $salaryIds)->get();
        $pdfData = [];

        foreach ($salaries as $sal) {
            $date = date('My', strtotime($sal->date));

            $rate_salary = $sal->salary_year->salary_grade->rate_salary;
            $ability = $sal->salary_year->ability;
            $fungtional_alw = $sal->salary_year->fungtional_alw;
            $family_alw = $sal->salary_year->family_alw;

            $total = $rate_salary + $ability + $fungtional_alw + $family_alw;

            $pdfData[] = [
                'sal' => $sal,
                'total' => $total
            ];
        }

        $pdf = PDF::loadView('salary.print_multiple', compact('pdfData'));
        return $pdf->setPaper('a4', 'potrait')->stream('Salaries_' . date('Ymd') . '.pdf');
    }

    public function download($id)
    {
        $sal = SalaryMonth::find($id);

        $date = date('My', strtotime($sal->date));

        if (!$sal) {
            dd("Salary with ID $id not found.");
        }

        $rate_salary = $sal->salary_year->salary_grade->rate_salary;
        $ability = $sal->salary_year->ability;
        $fungtional_alw = $sal->salary_year->fungtional_alw;
        $family_alw = $sal->salary_year->family_alw;

        $total = $rate_salary + $ability + $fungtional_alw + $family_alw;

        $pdf = PDF::loadView('salary.print', compact('sal', 'total'));
        return $pdf->setPaper('a5', 'landscape')->download('SAL_' . $date . '_' . $sal->salary_year->user->nik . '_' . $sal->salary_year->user->name . '.pdf');
    }

    public function printall()
    {
        $monthOpts = SalaryMonth::select(DB::raw('MONTH(date) as month'))
            ->distinct()
            ->pluck('month');

        $yearOpts = SalaryMonth::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year');

        $year = request()->input('year');
        $month = request()->input('month');

        $salaries = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->select(
                'users.nik as Emp Code',
                'users.name as Nama',
                'grade.name_grade as Grade',
                'grade.rate_salary',
                'salary_years.ability',
                'salary_years.fungtional_alw',
                'salary_years.family_alw',
                'salary_years.transport_alw',
                'salary_years.skill_alw',
                'salary_years.telephone_alw',
                'salary_years.bpjs',
                'salary_years.jamsostek',
                'salary_months.total_overtime',
                'salary_months.thr',
                'salary_months.bonus',
                'salary_months.incentive',
                'salary_months.union',
                'salary_months.absent',
                'salary_months.electricity',
                'salary_months.cooperative',
                'salary_months.pinjaman',
                'salary_months.other',
                'salary_months.date as salary_months_date',
                'salary_months.total_deduction',
                'salary_months.net_salary'
            )
            ->whereYear('salary_months.date', $year)
            ->whereMonth('salary_months.date', $month)
            ->orderBy('grade.name_grade', 'DESC')
            ->orderBy('users.name')
            ->get();

        $totalRateSalary = $salaries->sum('rate_salary');
        $totalAbility = $salaries->sum('ability');
        $totalFungtionalAlw = $salaries->sum('fungtional_alw');
        $totalSkillAlw = $salaries->sum('skill_alw');
        $totalFamilyAlw = $salaries->sum('family_alw');
        $totalTelephoneAlw = $salaries->sum('telephone_alw');
        $totalTransportAlw = $salaries->sum('transport_alw');
        $totalTotalOT = $salaries->sum('total_overtime');
        $totalIncentive = $salaries->sum('incentive');
        $totalThr = $salaries->sum('thr');
        $totalBonus = $salaries->sum('bonus');
        $totalPinjaman = $salaries->sum('pinjaman');
        $totalBpjs = $salaries->sum('bpjs');
        $totalJamsostek = $salaries->sum('jamsostek');
        $totalUnion = $salaries->sum('union');
        $totalOther = $salaries->sum('other');
        $totalAbsent = $salaries->sum('absent');
        $totalElectricity = $salaries->sum('electricity');
        $totalCooperative = $salaries->sum('cooperative');
        $totalTotalDed = $salaries->sum('total_deduction');
        $totalNetSalary = $salaries->sum('net_salary');

        $columns = [
            'Emp Code',
            'Nama',
            'Grade',
            'rate_salary',
            'ability',
            'fungtional_alw',
            'skill_alw',
            'family_alw',
            'telephone_alw',
            'transport_alw',
            'total_overtime',
            'incentive',
            'thr',
            'bonus',
            'pinjaman',
            'bpjs',
            'jamsostek',
            'union',
            'other',
            'absent',
            'electricity',
            'cooperative',
            'total_deduction',
            'net_salary'
        ];

        $displayColumns = [];
        foreach ($columns as $column) {
            if ($salaries->pluck($column)->filter()->isNotEmpty()) {
                $displayColumns[] = $column;
            }
        }

        $employeeIdentityColumns = ['Emp Code', 'Nama', 'Grade'];
        $salaryComponentColumns = ['rate_salary', 'ability', 'fungtional_alw', 'skill_alw', 'family_alw', 'telephone_alw', 'transport_alw', 'total_overtime', 'incentive', 'thr', 'bonus'];
        $deductionColumns = ['pinjaman', 'bpjs', 'jamsostek', 'union', 'other', 'absent', 'electricity', 'cooperative', 'total_deduction'];

        $employeeIdentityCols = count(array_intersect($displayColumns, $employeeIdentityColumns));
        $salaryComponentCols = count(array_intersect($displayColumns, $salaryComponentColumns));
        $deductionCols = count(array_intersect($displayColumns, $deductionColumns));

        $date = null;
        foreach ($salaries as $sal) {
            $date = date('F Y', strtotime($sal->salary_months_date));
        }

        if ($date) {
            $pdf = PDF::loadView('salary.printall_new_nd', compact(
                'salaries',
                'date',
                'displayColumns',
                'employeeIdentityCols'
                ,
                'salaryComponentCols',
                'deductionCols',
                'totalRateSalary',
                'totalAbility',
                'totalFungtionalAlw',
                'totalSkillAlw'
                ,
                'totalFamilyAlw',
                'totalTelephoneAlw',
                'totalTransportAlw',
                'totalTotalOT',
                'totalIncentive',
                'totalThr'
                ,
                'totalBonus',
                'totalPinjaman',
                'totalBpjs',
                'totalJamsostek',
                'totalUnion',
                'totalOther'
                ,
                'totalAbsent',
                'totalElectricity',
                'totalCooperative',
                'totalTotalDed',
                'totalNetSalary'
            ));
            return $pdf->setPaper(array(0, 0, 609.4488, 935.433), 'landscape')->stream('PrintAll.pdf');
        } else {
            return redirect()->route('salary.index');
        }
    }

    public function printallocation()
    {
        // $monthOpts = SalaryMonth::select(DB::raw('MONTH(date) as month'))
        //     ->distinct()
        //     ->pluck('month');

        // $yearOpts = SalaryMonth::select(DB::raw('YEAR(date) as year'))
        //     ->distinct()
        //     ->pluck('year');

        // $year = request()->input('year');
        // $month = request()->input('month');

        // $sal_allocation = SalaryMonth::with('salary_year')
        //     ->whereYear('date', $year)
        //     ->whereMonth('date', $month)
        //     ->get();

        // $data = [];
        // foreach ($sal_allocation as $item) {
        //     $allocations = json_decode($item->allocation);
        //     if ($allocations) {
        //         foreach ($allocations as $div) {
        //             $data[$div]['allocation'][] = $div;
        //             $data[$div]['rate_salary'][] = $item->salary_year->salary_grade->rate_salary;
        //             $data[$div]['ability'][] = $item->salary_year->ability;
        //             $data[$div]['fungtional_alw'][] = $item->salary_year->fungtional_alw;
        //             $data[$div]['family_alw'][] = $item->salary_year->family_alw;
        //             $data[$div]['transport_alw'][] = $item->salary_year->transport_alw;
        //             $data[$div]['adjustment'][] = $item->salary_year->adjustment;
        //             $data[$div]['bpjs'][] = $item->salary_year->bpjs;
        //             $data[$div]['jamsostek'][] = $item->salary_year->jamsostek;
        //             $data[$div]['total_ben'][] = $item->salary_year->total_ben;
        //             $data[$div]['total_ben_ded'][] = $item->salary_year->total_ben_ded;
        //             $data[$div]['total_overtime'][] = $item->total_overtime;
        //             $data[$div]['thr'][] = $item->thr;
        //             $data[$div]['bonus'][] = $item->bonus;
        //             $data[$div]['incentive'][] = $item->incentive;
        //             $data[$div]['union'][] = $item->union;
        //             $data[$div]['absent'][] = $item->absent;
        //             $data[$div]['electricity'][] = $item->electricity;
        //             $data[$div]['cooperative'][] = $item->cooperative;
        //             $data[$div]['gross_salary'][] = $item->gross_salary;
        //             $data[$div]['total_deduction'][] = $item->total_deduction;
        //             $data[$div]['net_salary'][] = $item->net_salary;
        //         }
        //     } else {
        //         return redirect()->route('salary.index');
        //     }
        // }

        // $finalResult = [];

        // foreach ($data as $div => $values) {
        //     $finalResult[] = [
        //         'allocation' => $div,
        //         'rate_salary' => array_sum($values['rate_salary']),
        //         'ability' => array_sum($values['ability']),
        //         'fungtional_alw' => array_sum($values['fungtional_alw']),
        //         'family_alw' => array_sum($values['family_alw']),
        //         'transport_alw' => array_sum($values['transport_alw']),
        //         'adjustment' => array_sum($values['adjustment']),
        //         'bpjs' => array_sum($values['bpjs']),
        //         'jamsostek' => array_sum($values['jamsostek']),
        //         'total_ben' => array_sum($values['total_ben']),
        //         'total_ben_ded' => array_sum($values['total_ben_ded']),
        //         'total_overtime' => array_sum($values['total_overtime']),
        //         'thr' => array_sum($values['thr']),
        //         'bonus' => array_sum($values['bonus']),
        //         'incentive' => array_sum($values['incentive']),
        //         'union' => array_sum($values['union']),
        //         'absent' => array_sum($values['absent']),
        //         'electricity' => array_sum($values['electricity']),
        //         'cooperative' => array_sum($values['cooperative']),
        //         'gross_salary' => array_sum($values['gross_salary']),
        //         'total_deduction' => array_sum($values['total_deduction']),
        //         'net_salary' => array_sum($values['net_salary']),
        //     ];
        // }

        // // At the end of the displayTable method
        // $grandTotal = [
        //     'rate_salary' => 0,
        //     'ability' => 0,
        //     'fungtional_alw' => 0,
        //     'family_alw' => 0,
        //     'transport_alw' => 0,
        //     'adjustment' => 0,
        //     'bpjs' => 0,
        //     'jamsostek' => 0,
        //     'total_ben' => 0,
        //     'total_ben_ded' => 0,
        //     'total_overtime' => 0,
        //     'thr' => 0,
        //     'bonus' => 0,
        //     'incentive' => 0,
        //     'union' => 0,
        //     'absent' => 0,
        //     'electricity' => 0,
        //     'cooperative' => 0,
        //     'gross_salary' => 0,
        //     'total_deduction' => 0,
        //     'net_salary' => 0,
        // ];

        // if (!empty($finalResult)) {
        //     $grandTotal = [
        //         'rate_salary' =>  array_sum(array_column($finalResult, 'rate_salary')),
        //         'ability' => array_sum(array_column($finalResult, 'ability')),
        //         'fungtional_alw' => array_sum(array_column($finalResult, 'fungtional_alw')),
        //         'family_alw' => array_sum(array_column($finalResult, 'family_alw')),
        //         'transport_alw' => array_sum(array_column($finalResult, 'transport_alw')),
        //         'adjustment' => array_sum(array_column($finalResult, 'adjustment')),
        //         'bpjs' => array_sum(array_column($finalResult, 'bpjs')),
        //         'jamsostek' => array_sum(array_column($finalResult, 'jamsostek')),
        //         'total_ben' => array_sum(array_column($finalResult, 'total_ben')),
        //         'total_ben_ded' => array_sum(array_column($finalResult, 'total_ben_ded')),
        //         'total_overtime' => array_sum(array_column($finalResult, 'total_overtime')),
        //         'thr' => array_sum(array_column($finalResult, 'thr')),
        //         'bonus' => array_sum(array_column($finalResult, 'bonus')),
        //         'incentive' => array_sum(array_column($finalResult, 'incentive')),
        //         'union' => array_sum(array_column($finalResult, 'union')),
        //         'absent' => array_sum(array_column($finalResult, 'absent')),
        //         'electricity' => array_sum(array_column($finalResult, 'electricity')),
        //         'cooperative' => array_sum(array_column($finalResult, 'cooperative')),
        //         'gross_salary' => array_sum(array_column($finalResult, 'gross_salary')),
        //         'total_deduction' => array_sum(array_column($finalResult, 'total_deduction')),
        //         'net_salary' => array_sum(array_column($finalResult, 'net_salary')),
        //     ];
        // }

        // $date = null;
        // foreach ($sal_allocation as $sal) {
        //     $date = date('F Y', strtotime($sal->date));
        // }

        // if ($date) {
        //     $pdf = PDF::loadView('salary.printallocation', compact('sal_allocation', 'date', 'finalResult', 'grandTotal'));
        //     return $pdf->setPaper('a4', 'landscape')->stream('PrintAllocation' . $date . '.pdf');
        // } else {
        //     return redirect()->route('salary.index');
        // }

        $pdf = PDF::loadView('salary.printallocation_new');
        return $pdf->setPaper(array(0, 0, 609.4488, 935.433), 'landscape')->stream('PrintAllocation.pdf');
    }

    public function send($id)
    {
        $sal = SalaryMonth::find($id);

        $date = date('My', strtotime($sal->date));

        if (!$sal) {
            dd("Salary with ID $id not found.");
        }

        $rate_salary = $sal->salary_year->salary_grade->rate_salary;
        $ability = $sal->salary_year->ability;
        $fungtional_alw = $sal->salary_year->fungtional_alw;
        $family_alw = $sal->salary_year->family_alw;

        $total = $rate_salary + $ability + $fungtional_alw + $family_alw;

        // $pdf = PDF::loadView('salary.print', compact('sal', 'total'));
        $days = Carbon::now()->subMonth(1)->format('mY');
        $dayss = Carbon::now();
        $day = ($dayss->hour < 12) ? "Pagi" : "Siang";

        $name = $sal->salary_year->user->name;
        $month = Carbon::parse($sal->date)->format('F');

        $customFileNames = $sal->salary_year->user->nik . $days . $id;
        $customFileName = Str::of($customFileNames)->toBase64();
        // Define the file path and name
        $filePath = storage_path('app/public') . '/' . $customFileName . '.pdf';
        $pdf = PDF::loadView('salary.print', compact('sal', 'total'));

        // Save the PDF file to the specified path
        file_put_contents($filePath, $pdf->output());

        $mediaUrl = $sal->salary_year->user->nik . $days . $id;
        $urls = Str::of($mediaUrl)->toBase64();

        $url = "https://bskp.blog:9000/pdf/" . $urls . ".pdf";

        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

        $twilio->messages->create(
            "whatsapp:+" . $sal->salary_year->user->no_telpon,
            // "whatsapp:+6283854428770",
            [
                "contentSid" => env('TWILIO_CONTENT_ID'),
                "messagingServiceSid" => env('TWILIO_SERVICE_ID'),
                "from" => "whatsapp:" . env('TWILIO_PHONE_NUMBER'),
                "contentVariables" => json_encode([
                    "1" => $day,
                    "2" => $name,
                    "3" => $month,
                    "4" => $url,
                ]),
            ]
        );

        // if ($is_send) {
        SalaryMonth::where('id', $id)->update(['is_send' => '1']);
        // }

        // dd($twilio);

        return redirect()->back();
    }

    public function send_batch(Request $request)
    {
        $year = request()->input('year');
        $month = request()->input('month');
        $date = $request->input('date');
        $status = $request->input('filter_status');

        $query = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select('users.name as nama', 'users.nik', 'users.id as id_users', 'users.no_telpon', 'salary_months.id as salary_month_id', 'salary_months.date as salary_month_date')
            ->whereYear('salary_months.date', $year)
            ->whereMonth('salary_months.date', $month)
            ->where('users.id_status', $status)
            ->get();

        // $query = DB::table('salary_months')
        //     ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
        //     ->join('salary_grades', 'salary_years.id_salary_grade', '=', 'salary_grades.id')
        //     ->join('users', 'users.id', '=', 'salary_years.id_user')
        //     ->join('grades', 'salary_grades.id_grade', '=', 'grades.id')
        //     ->select('users.name as nama', 'users.nik', 'users.id as id_users', 'users.no_telpon', 'salary_months.id as salary_month_id', 'salary_months.date as salary_month_date')
        //     ->whereYear('salary_months.date', $year)
        //     ->whereMonth('salary_months.date', $month)
        //     ->where('users.id_status', $status)
        //     ->where('users.id_dept', 1)
        //     ->get();

        // dd($query);

        foreach ($query as $data) {
            $days = Carbon::now()->subMonth(1)->format('mY');
            $dayss = Carbon::now();
            $day = ($dayss->hour < 12) ? "Pagi" : "Siang";

            $name = $data->nama;
            $month = Carbon::parse($data->salary_month_date)->format('F');

            $customFileNames = $data->nik . $days . $data->salary_month_id;
            $customFileName = Str::of($customFileNames)->toBase64();
            $filePath = storage_path('app/public') . '/' . $customFileName . '.pdf';

            $id = $data->salary_month_id;
            $sal = SalaryMonth::find($id);

            if (!$sal) {
                dd("Salary with ID $id not found.");
            }

            $rate_salary = $sal->salary_year->salary_grade->rate_salary;
            $ability = $sal->salary_year->ability;
            $fungtional_alw = $sal->salary_year->fungtional_alw;
            $family_alw = $sal->salary_year->family_alw;
            $total = $rate_salary + $ability + $fungtional_alw + $family_alw;
            $pdf = PDF::loadView('salary.print', compact('sal', 'total'));

            file_put_contents($filePath, $pdf->output());

            $mediaUrl = $data->nik . $days . $data->salary_month_id;
            $urls = Str::of($mediaUrl)->toBase64();

            // $url = "https://bskp.blog:9000/pdf/" . $urls . ".pdf" . "(This message containt dangerous file, please dont open it!)";
            $url = "This message containt dangerous file, please dont open it!";

            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

            $is_send = $twilio->messages->create(
                "whatsapp:+" . $data->no_telpon,
                [
                    "contentSid" => env('TWILIO_CONTENT_ID'),
                    "messagingServiceSid" => env('TWILIO_SERVICE_ID'),
                    "from" => "whatsapp:" . env('TWILIO_PHONE_NUMBER'),
                    "contentVariables" => json_encode([
                        "1" => $day,
                        "2" => $name,
                        "3" => $month,
                        "4" => $url,
                    ]),
                ]
            );
        }

        if ($is_send) {
            SalaryMonth::where('id', $id)->update(['is_send' => '1']);
        }

        return redirect()->back();
    }

    public function send_report()
    {
        $title = 'Send Historical Slip';

        $years = SalaryMonth::distinct('date')->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $months_filter = SalaryMonth::select('date')->distinct()->pluck('date')->map(function ($date) {
            $carbonDate = Carbon::parse($date);
            return [
                'value' => $carbonDate->format('m'),
                'label' => $carbonDate->format('F'),
            ];
        })->unique()->toArray();

        $selectedMonth = trim(request()->input('filter_month', ''));

        $statuses = User::distinct('status')->pluck('status')->toArray();

        $currentYear = Carbon::now()->year;
        $notCurrentMonth = Carbon::now()->subMonth()->month;

        $rawData = DB::table('salary_months')
            ->join('salary_years', 'salary_months.id_salary_year', 'salary_years.id')
            ->join('users', 'salary_years.nik', '=', 'users.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select(
                'users.nik',
                'users.no_telpon',
                'users.name',
                'users.dept',
                'users.jabatan',
                'users.status',
                'salary_years.id as salary_year_id',
                'salary_years.year',
                'salary_months.id as salary_month_id',
                'salary_months.is_send',
                'salary_months.date',
                DB::raw('MONTH(salary_months.date) as month')
            )
            ->whereYear('salary_months.date', $currentYear)
            // ->whereMonth('salary_months.date', $notCurrentMonth)
            ->get();

        // dd($rawData);

        $rawData->transform(function ($item) {
            $phone = preg_replace('/[^0-9]/', '', $item->no_telpon);

            if (substr($phone, 0, 1) == '0') {
                $phone = '+62' . substr($phone, 1);
            } elseif (substr($phone, 0, 2) != '62') {
                $phone = '+62' . $phone;
            } else {
                $phone = '+' . $phone;
            }

            $item->no_telpon = preg_replace('/(\+62)(\d{3})(\d{4})(\d{4})/', '$1 $2-$3-$4', $phone);

            return $item;
        });

        $months = $rawData->pluck('month')->unique()->sort()->values()->toArray();

        $groupedData = $rawData->groupBy('nik');

        return view('salary.send-history', [
            'years' => $years,
            'months_filter' => $months_filter,
            'months' => $months,
            'statuses' => $statuses,
            'title' => $title,
            'data' => $groupedData,
            'selectedMonth' => $selectedMonth
        ]);
    }

    // public function send_checked(Request $request)
    // {
    //     $selectedIds = $request->input('salary_ids');
    //     $months = $request->input('filter_month');

    //     $query = DB::table('salary_months')
    //         ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
    //         ->join('users', 'users.nik', '=', 'salary_years.nik')
    //         ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
    //         ->select('users.name as nama', 'users.nik', 'users.id as id_users', 'users.no_telpon', 'salary_months.id as salary_month_id', 'salary_months.date as salary_month_date')
    //         ->whereIn('salary_years.id', $selectedIds)
    //         ->whereMonth('salary_months.date', $months)
    //         ->get();

    //     foreach ($query as $data) {
    //         $days = Carbon::now()->subMonth(1)->format('mY');
    //         $dayss = Carbon::now();
    //         $day = ($dayss->hour < 12) ? "Pagi" : "Siang";

    //         $name = $data->nama;
    //         $month = Carbon::parse($data->salary_month_date)->format('F');

    //         $customFileNames = $data->nik . $days . $data->salary_month_id;
    //         $customFileName = Str::of($customFileNames)->toBase64();
    //         $filePath = storage_path('app/public') . '/' . $customFileName . '.pdf';

    //         $id = $data->salary_month_id;



    //         // $sal = DB::table('salary_months')
    //         //     ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
    //         //     ->join('users', 'users.nik', '=', 'salary_years.nik')
    //         //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
    //         //     ->select('salary_months.date as salary_month_date', 'salary_months.*', 'salary_years.*', 'users.*', 'grade.*')
    //         //     ->where('salary_months.id', $id)
    //         //     ->first();

    //         $sal = DB::table('salary_months')
    //             ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
    //             ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
    //             ->join('users', 'users.nik', '=', 'salary_years.nik')
    //             ->select(
    //                 'users.nik as Emp_Code',
    //                 'users.name as Nama',
    //                 'users.status as Status',
    //                 'users.dept as Dept',
    //                 'users.jabatan as Jabatan',
    //                 'users.start_work_user',
    //                 'grade.name_grade as Grade',
    //                 'grade.rate_salary',
    //                 'salary_years.*',
    //                 'salary_months.*',
    //                 'salary_months.absent',
    //                 'salary_months.electricity',
    //                 'salary_months.cooperative',
    //                 'salary_months.pinjaman',
    //                 'salary_months.other',
    //                 'salary_months.date as salary_months_date',
    //                 'salary_months.total_deduction',
    //                 'salary_months.net_salary'
    //             )
    //             ->where('salary_months.id', $id)
    //             ->first();

    //         // $sal = SalaryMonth::find($id);

    //         // dd($sal, $sal->salary_year->nik);

    //         if (!$sal) {
    //             dd("Salary with ID $id not found.");
    //         }

    //         // $rate_salary = $sal->salary_year->salary_grade->rate_salary;
    //         // $ability = $sal->salary_year->ability;
    //         // $fungtional_alw = $sal->salary_year->fungtional_alw;
    //         // $family_alw = $sal->salary_year->family_alw;
    //         // $total = $rate_salary + $ability + $fungtional_alw + $family_alw;

    //         $rate_salary = $sal->rate_salary;
    //         $ability = $sal->ability;
    //         $fungtional_alw = $sal->fungtional_alw;
    //         $family_alw = $sal->family_alw;
    //         $total = $rate_salary + $ability + $fungtional_alw + $family_alw;
    //         $pdf = PDF::loadView('salary.print', compact('sal', 'total'));

    //         file_put_contents($filePath, $pdf->output());

    //         $mediaUrl = $data->nik . $days . $data->salary_month_id;
    //         $urls = Str::of($mediaUrl)->toBase64();

    //         // $url = "https://bskp.blog:9000/pdf/" . $urls . ".pdf" . "(This message containt dangerous file, please dont open it!)";
    //         $url = "This message containt dangerous file, please dont open it!";

    //         $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

    //         $is_send = $twilio->messages->create(
    //             "whatsapp:+" . $data->no_telpon,
    //             [
    //                 "contentSid" => env('TWILIO_CONTENT_ID'),
    //                 "messagingServiceSid" => env('TWILIO_SERVICE_ID'),
    //                 "from" => "whatsapp:" . env('TWILIO_PHONE_NUMBER'),
    //                 "contentVariables" => json_encode([
    //                     "1" => $day,
    //                     "2" => $name,
    //                     "3" => $month,
    //                     "4" => $url,
    //                 ]),
    //             ]
    //         );
    //     }

    //     if ($is_send) {
    //         SalaryMonth::where('id', $id)->update(['is_send' => '1']);
    //     }

    //     return redirect()->back();
    // }

    public function send_checked(Request $request)
    {
        $selectedIds = $request->input('salary_ids');
        $months = $request->input('filter_month');

        // dd($selectedIds, $months);

        //send to job
        SendCheckedSalaryJob::dispatch($selectedIds, $months);

        return redirect()->back();
    }

    public function summary()
    {
        $title = 'Summary';
        $emp = User::orderBy('name', 'asc')->whereIn('status', ['Manager', 'Staff', 'Monthly'])->where('active', 'yes')->get();
        $years = SalaryYear::distinct('year')->pluck('year')->toArray();

        return view('salary.summary', compact('title', 'emp', 'years'));
    }

    public function result()
    {
        $title = 'Summary';
        $empFilter = request()->input('id_user', null);
        $yearFilter = request()->input('year', null);

        // $data = DB::table('salary_months')
        //     ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
        //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
        //     ->join('users', 'users.nik', '=', 'salary_years.nik')
        //     ->select('salary_months.*', 'salary_years.*', 'users.*', 'grade.*', 'salary_months.date as salary_month_date')
        //     ->where('users.id', $empFilter)
        //     ->get();

        $data = DB::table('all_salary_data')
            ->join('users', 'users.nik', '=', 'all_salary_data.nik')
            ->select(
                'users.nik',
                'users.name',
                'users.dept',
                'users.status',
                'users.jabatan',
                'all_salary_data.year',
                'all_salary_data.date',
                'all_salary_data.salary_grade',
                'all_salary_data.rate_salary',
                'all_salary_data.ability',
                'all_salary_data.fungtional_alw',
                'all_salary_data.family_alw',
                'all_salary_data.transport_alw',
                'all_salary_data.skill_alw',
                'all_salary_data.telephone_alw',
                'all_salary_data.adjustment',
                'all_salary_data.bpjs',
                'all_salary_data.jamsostek',
                'all_salary_data.total_ben',
                'all_salary_data.total_ben_ded',
                'all_salary_data.total_overtime',
                'all_salary_data.thr',
                'all_salary_data.bonus',
                'all_salary_data.incentive',
                'all_salary_data.union',
                'all_salary_data.absent',
                'all_salary_data.electricity',
                'all_salary_data.cooperative',
                'all_salary_data.pinjaman',
                'all_salary_data.other',
                'all_salary_data.gross_salary',
                'all_salary_data.total_deduction',
                'all_salary_data.net_salary',
            )
            ->where('users.nik', $empFilter)
            ->get();

        $name = User::where('nik', $empFilter)->select('nik', 'name')->first();

        return view('salary.result', compact('title', 'empFilter', 'yearFilter', 'data', 'name'));
    }

    public function historical()
    {
        $title = 'Summary Historical Grade';

        $currentYear = Carbon::now()->year;

        $years = [
            $currentYear - 2,
            $currentYear - 1,
            $currentYear,
        ];

        // $rawData = DB::table('salary_years')
        //     ->join('users', 'salary_years.nik', '=', 'users.nik')
        //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
        //     ->select(
        //         'users.nik',
        //         'users.name',
        //         'users.dept',
        //         'users.jabatan',
        //         'users.status',
        //         'grade.name_grade',
        //         'salary_years.year'
        //     )
        //     ->whereIn('salary_years.year', $years)
        //     ->get();


        $rawData = DB::table('all_salary_data')
            ->join('users', 'all_salary_data.nik', '=', 'users.nik')
            ->select(
                'users.name',
                'users.dept',
                'users.jabatan',
                'users.status',
                'all_salary_data.nik',
                'all_salary_data.salary_grade',
                'all_salary_data.date',
                'all_salary_data.year'
            )
            ->whereIn('all_salary_data.year', $years)
            ->get();

        $groupedData = [];
        foreach ($rawData as $row) {
            $key = $row->nik . '-' . $row->name . '-' . $row->dept . '-' . $row->jabatan . '-' . $row->status;
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'nik' => $row->nik,
                    'name' => $row->name,
                    'status' => $row->status,
                    'dept' => $row->dept,
                    'jabatan' => $row->jabatan,
                ];
                foreach ($years as $year) {
                    $groupedData[$key]['grade_' . $year] = [];
                }
            }

            $gradeKey = 'grade_' . $row->year;
            $groupedData[$key][$gradeKey][$row->salary_grade] = true;
        }

        foreach ($groupedData as &$data) {
            foreach ($years as $year) {
                $yearKey = 'grade_' . $year;
                $data[$yearKey] = implode(' / ', array_keys($data[$yearKey]));
            }
        }

        return view('salary.historical', ['data' => $groupedData, 'title' => $title, 'years' => $years]);
    }

    public function historical_detail($id)
    {
        $title = 'Individual - Historical Grade';

        $years = SalaryYear::select('year')->distinct()->get()->pluck('year')->sort()->values();

        // $biodata = DB::table('salary_years')
        //     ->join('users', 'salary_years.nik', '=', 'users.nik')
        //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
        //     ->select(
        //         'users.nik',
        //         'users.name',
        //         'users.dept',
        //         'users.jabatan',
        //         'users.status',
        //         'grade.name_grade',
        //         'salary_years.year'
        //     )
        //     ->where('users.nik', $id)
        //     ->whereIn('salary_years.year', $years)
        //     ->first();

        $biodata = DB::table('all_salary_data')
            ->join('users', 'all_salary_data.nik', '=', 'users.nik')
            ->select(
                'users.nik',
                'users.name',
                'users.dept',
                'users.jabatan',
                'users.status',
                'all_salary_data.salary_grade',
                'all_salary_data.year'
            )
            ->where('users.nik', $id)
            ->whereIn('all_salary_data.year', $years)
            ->first();

        // $rawData = DB::table('salary_years')
        //     ->join('users', 'salary_years.nik', '=', 'users.nik')
        //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
        //     ->select(
        //         'users.nik',
        //         'users.name',
        //         'users.dept',
        //         'users.jabatan',
        //         'users.status',
        //         'grade.name_grade',
        //         'salary_years.year'
        //     )
        //     ->where('users.nik', $id)
        //     ->whereIn('salary_years.year', $years)
        //     ->get();

        $rawData = DB::table('all_salary_data')
            ->join('users', 'all_salary_data.nik', '=', 'users.nik')
            ->select(
                'users.nik',
                'users.name',
                'users.dept',
                'users.jabatan',
                'users.status',
                'all_salary_data.salary_grade',
                'all_salary_data.year'
            )
            ->where('users.nik', $id)
            ->whereIn('all_salary_data.year', $years)
            ->get();

        $groupedData = [];
        foreach ($rawData as $row) {
            $key = $row->nik . '-' . $row->name . '-' . $row->dept . '-' . $row->jabatan . '-' . $row->status;
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'nik' => $row->nik,
                    'name' => $row->name,
                    'status' => $row->status,
                    'dept' => $row->dept,
                    'jabatan' => $row->jabatan,
                    'grade' => []
                ];
            }
            $groupedData[$key]['grade'][$row->year][] = $row->salary_grade;
        }

        foreach ($groupedData as &$data) {
            foreach ($years as $year) {
                // $data['name_status'][$year] = isset($data['name_status'][$year]) ? implode(' / ', array_unique($data['name_status'][$year])) : '-';
                // $data['name_dept'][$year] = isset($data['name_dept'][$year]) ? implode(' / ', array_unique($data['name_dept'][$year])) : '-';
                // $data['name_job'][$year] = isset($data['name_job'][$year]) ? implode(' / ', array_unique($data['name_job'][$year])) : '-';
                $data['grade'][$year] = isset($data['grade'][$year]) ? implode(' / ', array_unique($data['grade'][$year])) : '-';
            }
        }

        return view('salary.historical-detail', ['data' => $groupedData, 'title' => $title, 'years' => $years, 'biodata' => $biodata]);
    }

    public function salary_monitoring_index()
    {
        $title = 'Salary Monitoring';
        $currentYear = Carbon::now()->year;

        $data = DB::table('salary_months')
            ->join('salary_years', 'salary_months.id_salary_year', '=', 'salary_years.id')
            ->join('users', 'salary_years.nik', '=', 'users.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->where('salary_years.year', $currentYear)
            // ->where('salary_months.is_approved', 1)
            ->select(
                'users.status',
                'salary_years.year',
                'salary_months.date',
                'grade.rate_salary',
                'salary_years.fungtional_alw',
                'salary_years.family_alw',
                'salary_years.transport_alw',
                'salary_years.telephone_alw',
                'salary_years.skill_alw',
                'salary_months.total_overtime',
                'salary_months.incentive',
                'salary_months.net_salary'
            )
            ->get();

        $groupedData = $data->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('M-y'); // Mengelompokkan berdasarkan bulan dengan format 'May-24'
        })->map(function ($monthData) {
            return $monthData->groupBy('status');
        });

        $counts = $groupedData->map(function ($monthData) {
            return $monthData->map(function ($group) {
                $totalFungtional = $group->sum('fungtional_alw');
                $totalFamily = $group->sum('family_alw');
                $totalTransport = $group->sum('transport_alw');
                $totalTelephone = $group->sum('telephone_alw');
                $totalSkill = $group->sum('skill_alw');
                $totalOvertime = $group->sum('total_overtime');
                $totalIncentive = $group->sum('incentive');
                $employeeCount = $group->count();
                $totalNetSalary = $group->sum('net_salary');
                $totalRateSalary = $group->sum('rate_salary');

                return [
                    'employee_count' => $employeeCount,
                    'total_rate_salary' => $totalRateSalary,
                    'total_salary' => $totalNetSalary,
                    'total_allowance' => $totalFungtional + $totalFamily + $totalTransport + $totalTelephone + $totalSkill,
                    'total_overtime_incentive' => $totalOvertime + $totalIncentive,
                    'average_salary' => $employeeCount > 0 ? ($totalNetSalary / $employeeCount) : 0,
                ];
            });
        });

        return view('salary.salary-monitoring', [
            'title' => $title,
            'currentYear' => $currentYear,
            'counts' => $counts,
        ]);
    }

    public function salary_monitoring_approve(Request $request)
    {
        $year = $request->year;
        $parseMonth = Carbon::createFromFormat('M-y', $request->month);
        $monthNumber = $parseMonth->format('m');
        $status = $request->status;

        $data = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->join('users', 'salary_years.nik', '=', 'users.nik')
            ->select(
                'salary_months.*',
                'grade.name_grade',
                'grade.rate_salary',
                'salary_years.nik',
                'salary_years.year',
                'salary_years.ability',
                'salary_years.fungtional_alw',
                'salary_years.family_alw',
                'salary_years.transport_alw',
                'salary_years.skill_alw',
                'salary_years.telephone_alw',
                'salary_years.adjustment',
                'salary_years.bpjs',
                'salary_years.jamsostek',
                'salary_years.total_ben',
                'salary_years.total_ben_ded'
            )
            ->whereYear('salary_months.date', $year)
            ->whereMonth('salary_months.date', $monthNumber)
            ->where('users.status', $status)
            ->get();

        if ($data != null) {
            foreach ($data as $emp) {
                $approve = DB::table('salary_months')
                    ->where('id', $emp->id)
                    ->update([
                        'is_checked' => 1,
                        'is_approved' => 1
                    ]);
            }
        }

        if ($approve) {
            $created_at = Carbon::now();
            foreach ($data as $emp) {
                DB::table('all_salary_data')->insert([
                    'nik' => $emp->nik,
                    'salary_grade' => $emp->name_grade,
                    'rate_salary' => $emp->rate_salary,
                    'date' => $emp->date,
                    'year' => $emp->year,
                    'ability' => $emp->ability,
                    'fungtional_alw' => $emp->fungtional_alw,
                    'family_alw' => $emp->family_alw,
                    'transport_alw' => $emp->transport_alw,
                    'skill_alw' => $emp->skill_alw,
                    'telephone_alw' => $emp->telephone_alw,
                    'adjustment' => $emp->adjustment,
                    'bpjs' => $emp->bpjs,
                    'jamsostek' => $emp->jamsostek,
                    'total_ben' => $emp->total_ben,
                    'total_ben_ded' => $emp->total_ben_ded,
                    'total_overtime' => $emp->total_overtime,
                    'thr' => $emp->thr,
                    'bonus' => $emp->bonus,
                    'incentive' => $emp->incentive,
                    'union' => $emp->union,
                    'absent' => $emp->absent,
                    'electricity' => $emp->electricity,
                    'cooperative' => $emp->cooperative,
                    'pinjaman' => $emp->pinjaman,
                    'other' => $emp->other,
                    'gross_salary' => $emp->gross_salary,
                    'total_deduction' => $emp->total_deduction,
                    'net_salary' => $emp->net_salary,
                    'created_at' => $created_at,
                ]);
            }
            return redirect()->back()->with('success', 'Data berhasil disimpan ke database.');
        } else {
            return redirect()->back()->with('error', 'Data tidak berhasil disimpan ke database.');
        }

    }
}
