<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SalaryMonth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use PDF;
use Twilio\Rest\Client;

class SendCheckedSalaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $selectedIds;
    protected $months;

    /**
     * Create a new job instance.
     */
    public function __construct($selectedIds, $months)
    {
        $this->selectedIds = $selectedIds;
        $this->months = $months;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $query = DB::table('salary_months')
            ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            ->join('users', 'users.nik', '=', 'salary_years.nik')
            ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            ->select('users.name as nama', 'users.nik', 'users.id as id_users', 'users.no_telpon', 'salary_months.id as salary_month_id', 'salary_months.date as salary_month_date')
            ->whereIn('salary_years.id', $this->selectedIds)
            ->whereMonth('salary_months.date', $this->months)
            ->get();

        foreach ($query as $data) {
            $days = Carbon::now()->subMonth(1)->format('mY');
            $dayss = Carbon::now();
            $day = ($dayss->hour < 12) ? "Pagi" : "Siang";

            $name = $data->nama;
            $month = Carbon::parse($data->salary_month_date)->isoFormat('MMMM Y');

            $customFileNames = $data->nik . $days . $data->salary_month_id;
            $customFileName = Str::of($customFileNames)->toBase64();
            $filePath = storage_path('app/public') . '/' . $customFileName . '.pdf';

            $id = $data->salary_month_id;

            // $sal = DB::table('salary_months')
            //     ->join('salary_years', 'salary_years.id', '=', 'salary_months.id_salary_year')
            //     ->join('users', 'users.nik', '=', 'salary_years.nik')
            //     ->join('grade', 'salary_years.id_salary_grade', '=', 'grade.id')
            //     ->select('salary_months.*', 'salary_months.date as salary_month_date', 'salary_years.*', 'users.*', 'grade.*')
            //     ->where('salary_months.id', $id)
            //     ->first();

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

            if (!$sal) {
                dd("Salary with ID $id not found.");
            }

            $rate_salary = $sal->rate_salary;
            $ability = $sal->ability;
            $fungtional_alw = $sal->fungtional_alw;
            $family_alw = $sal->family_alw;
            $total = $rate_salary + $ability + $fungtional_alw + $family_alw;
            $pdf = PDF::loadView('salary.print', compact('sal', 'total'))->setPaper('a5', 'landscape');

            file_put_contents($filePath, $pdf->output());

            $mediaUrl = $data->nik . $days . $data->salary_month_id;
            $customFileName = (string) Str::of($mediaUrl)->toBase64();

            // Store the PDF to the public server via an API call
            $http = new \GuzzleHttp\Client();
            $response = $http->post('https://bskp.blog:9000/api/upload-pdf', [
                'multipart' => [
                    [
                        'name' => 'pdf',
                        'contents' => $pdf->output(),
                        'filename' => $customFileName,
                    ],
                    [
                        'name' => 'filename',
                        'contents' => $customFileName,
                    ],
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['path'])) {
                dd("Error uploading PDF");
            }

            // $url = "https://bskp.blog:9000/storage/pdf/" . $customFileName . '.pdf';
            $url = $customFileName . '.pdf';

            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

            $is_send = $twilio->messages->create(
                "whatsapp:+" . $data->no_telpon,
                [
                    "contentSid" => env('TWILIO_CONTENT_ID'),
                    "messagingServiceSid" => env('TWILIO_SERVICE_ID'),
                    "from" => "whatsapp:" . env('TWILIO_PHONE_NUMBER'),
                    "contentVariables" => json_encode([
                        "1" => $day,
                        "2" => $month,
                        "3" => $name,
                        "4" => $url,
                    ]),
                ]
            );

            if ($is_send) {
                SalaryMonth::where('id', $id)->update(['is_send' => '1']);
            }
        }
    }
}
