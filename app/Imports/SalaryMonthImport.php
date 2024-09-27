<?php

namespace App\Imports;

use App\Models\SalaryMonth;
use App\Models\SalaryYear;
use App\Models\SalaryGrade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class SalaryMonthImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        $getRateSalary = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select('grade.rate_salary', 'salary_years.ability', 'salary_years.fungtional_alw', 'salary_years.family_alw',
                    'salary_years.transport_alw', 'salary_years.telephone_alw', 'salary_years.skill_alw', 'salary_years.adjustment')
                    // ->where('salary_years.used', '1')
                    ->where('salary_months.id', $row['id'])
            ->first();

        $rateSalary = $getRateSalary->rate_salary;
        $ability = $getRateSalary->ability;
        $fungtional_alw = $getRateSalary->fungtional_alw;
        $family_alw = $getRateSalary->family_alw;
        $transport_alw = $getRateSalary->transport_alw;
        $telephone_alw = $getRateSalary->telephone_alw;
        $skill_alw = $getRateSalary->skill_alw;
        $adjustment = $getRateSalary->adjustment;

        $hourCall = $row['hour_call'];
        $total_overtime = (($rateSalary + $ability) / 173) * $hourCall;

        $thr = $row['thr'];
        $bonus = $row['bonus'];
        $incentive = $row['incentive'];
        $union = $row['union'];
        $absent = $row['absent'];
        $electricity = $row['electricity'];
        $cooperative = $row['cooperative'];
        $pinjaman = $row['pinjaman'];
        $other = $row['other'];

        $gross_sal = $rateSalary + $ability + $fungtional_alw + $family_alw + $transport_alw + $skill_alw + $telephone_alw +
                    $adjustment + $total_overtime + $thr + $bonus + $incentive;

        $total = $rateSalary + $ability + $family_alw;

        $bpjs = ($total > 12000000) ? 12000000 * 0.01 : $total * 0.01;
        $jamsostek = $total * 0.02;
        $total_ben = $jamsostek;
        $total_ben_ded = $jamsostek;

        // Hitung total deduction
        $total_deduction = $bpjs + $jamsostek + $union + $absent + $electricity + $cooperative + $pinjaman + $other;

        $gaji_bersih = $gross_sal - ($bpjs + $jamsostek + $union + $absent + $electricity + $pinjaman + $other);

        $net_salary = $gaji_bersih - $cooperative;

        // dd($gross_sal, $bpjs, $jamsostek, $total_ben, $total_deduction, $total);

        // Hitung net salary
        // $net_salary = $gross_sal - $total_deduction;

        return SalaryMonth::updateOrCreate(
            [
                'id' => $row['id'],
                'date' => $row['date'],
            ],
            [
                'hour_call'       => $row['hour_call'],
                'total_overtime'  => $total_overtime,
                'thr'             => $thr,
                'bonus'           => $bonus,
                'incentive'       => $incentive,
                'union'           => $union,
                'absent'          => $absent,
                'electricity'     => $electricity,
                'cooperative'     => $cooperative,
                'pinjaman'        => $pinjaman,
                'other'           => $other,
                'gross_salary'    => $gross_sal,
                'total_deduction' => $total_deduction,
                'net_salary'      => $net_salary,
                'bpjs'            => $bpjs,
                'jamsostek'       => $jamsostek,
                'total_ben'       => $total_ben,
                'total_ben_ded'   => $total_ben_ded,
            ]
        );
    }
}
