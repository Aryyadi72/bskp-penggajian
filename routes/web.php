<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalaryGradeController;
use App\Http\Controllers\SalaryYearController;
use App\Http\Controllers\SalaryMonthController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\SomeController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::middleware(['jwt.verify'])->group(function () {
//     Route::get('/protected-route', [SomeController::class, 'someProtectedMethod']);
// });

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/historical', [SalaryController::class, 'historical'])->name('historical');
});

// DashboardController
// -------------------------------------------------------------------
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::resource('user', UserController::class);
// -------------------------------------------------------------------

// route edit tanpa parameter id, karena id nya menggunakan request
// Route::get('/salarygrade/edit', [SalaryGradeController::class, 'edit'])->name('salarygrade.edit');
// Route::put('/salarygrade/update', [SalaryGradeController::class, 'update'])->name('salarygrade.update_multiple');
// Route::get('/salary-year/edit', [SalaryYearController::class, 'edit'])->name('salary-year.edit');
// Route::put('/salary-year/update', [SalaryYearController::class, 'update'])->name('salary-year.update_multiple');
// Route::get('/salary-month/edit', [SalaryMonthController::class, 'edit'])->name('salary-month.edit');
// Route::put('/salary-month/update', [SalaryMonthController::class, 'update'])->name('salary-month.update_multiple');

// Route::resource('salary-year', SalaryYearController::class);
// Route::resource('salarygrade', SalaryGradeController::class);
// Route::resource('salary-month', SalaryMonthController::class);


// SalaryYearController
// -------------------------------------------------------------------
Route::get('/salary-year', [SalaryYearController::class, 'index'])->name('salary-year');
Route::get('/salary-year/filter', [SalaryYearController::class, 'filter'])->name('salary-year.filter');
Route::get('/salary-year/create', [SalaryYearController::class, 'create'])->name('salary-year.create');
Route::post('/salary-year/store', [SalaryYearController::class, 'store'])->name('salary-year.store');
Route::get('/salary-year/edit', [SalaryYearController::class, 'edit'])->name('salary-year.edit');
Route::put('/salary-year/update', [SalaryYearController::class, 'update'])->name('salary-year.update');
Route::get('/salary-year/filter-new', [SalaryYearController::class, 'filter_new'])->name('salary-year.filter-new');
Route::get('/salary-year/get-emp', [SalaryYearController::class, 'get_emp'])->name('salary-year.get-emp');
Route::post('/salary-year/create-new', [SalaryYearController::class, 'create_new'])->name('salary-year.create-new');
Route::get('/salary-year/get-rate-salary', [SalaryYearController::class, 'get_rate_salary'])->name('salary-year.get-rate-salary');
Route::post('/salary-year/store-new', [SalaryYearController::class, 'store_new'])->name('salary-year.store-new');
// -------------------------------------------------------------------

// SalaryGradeController
// -------------------------------------------------------------------
Route::get('/salarygrade', [SalaryGradeController::class, 'index'])->name('salarygrade');
Route::get('/salarygrade/filter', [SalaryGradeController::class, 'filter'])->name('salarygrade.filter');
Route::get('/salarygrade/create', [SalaryGradeController::class, 'create'])->name('salarygrade.create');
Route::post('/salarygrade/store', [SalaryGradeController::class, 'store'])->name('salarygrade.store');
Route::get('/salarygrade/edit', [SalaryGradeController::class, 'edit'])->name('salarygrade.edit');
Route::put('/salarygrade/update', [SalaryGradeController::class, 'update'])->name('salarygrade.update');
// -------------------------------------------------------------------

// SalaryMonthController
// -------------------------------------------------------------------
Route::get('/salary-month', [SalaryMonthController::class, 'index'])->name('salary-month');
Route::get('/salary-month/filter', [SalaryMonthController::class, 'filter'])->name('salary-month.filter');
Route::get('/salary-month/create', [SalaryMonthController::class, 'create'])->name('salary-month.create');
Route::post('/salary-month/store', [SalaryMonthController::class, 'store'])->name('salary-month.store');
Route::get('/salary-month/edit', [SalaryMonthController::class, 'edit'])->name('salary-month.edit');
Route::put('/salary-month/update', [SalaryMonthController::class, 'update'])->name('salary-month.update');
Route::post('/salary-month/export', [SalaryMonthController::class, 'export'])->name('salary-month.export');
Route::post('/salary-month/import', [SalaryMonthController::class, 'import'])->name('salary-month.import');
// -------------------------------------------------------------------


// Master
// -------------------------------------------------------------------
Route::resource('status', StatusController::class);
Route::resource('grade', GradeController::class);
Route::resource('departement', DeptController::class);
Route::resource('job', JobController::class);
// -------------------------------------------------------------------

// SalaryController
// -------------------------------------------------------------------
Route::resource('salary', SalaryController::class);
// Route::get('/salary', [SalaryController::class, 'index'])->name('salary');
Route::post('/is-checked', [SalaryController::class, 'salary_check'])->name('salary-check');
Route::post('/is-approved', [SalaryController::class, 'salary_approved'])->name('salary-approved');
Route::get('/summary', [SalaryController::class, 'summary'])->name('summary');
Route::get('/result', [SalaryController::class, 'result'])->name('result');
// Route::get('/historical', [SalaryController::class, 'historical'])->name('historical');
Route::get('/historical/{id}', [SalaryController::class, 'historical_detail'])->name('historical-detail');

// Print Salary Data
Route::get('/print-index', [SalaryController::class, 'salary_print']);
Route::get('/print-pdf/{id}', [SalaryController::class, 'print']);
Route::get('/download-pdf/{id}', [SalaryController::class, 'download']);
Route::get('/print-all', [SalaryController::class, 'printall']);
Route::get('/print-allocation', [SalaryController::class, 'printallocation']);
Route::post('/salary/print-multiple', [SalaryController::class, 'printMultiple'])->name('salary.printMultiple');

// Send Salary Data
Route::post('/send-whatsapp-checked', [SalaryController::class, 'send_checked'])->name('send-whatsapp-checked');
Route::post('/send-whatsapp', [SalaryController::class, 'send_batch'])->name('send-whatsapp-batch');
Route::get('/send-whatsapp/{id}', [SalaryController::class, 'send'])->name('send-whatsapp');
Route::get('/list-is-send', [SalaryController::class, 'send_report'])->name('list-is-send');

// Route::post('/is-checked', [SalaryController::class, 'salary_check'])->name('salary-check');
// Route::post('/is-approved', [SalaryController::class, 'salary_approved'])->name('salary-approved');

Route::get('/salary-monitoring', [SalaryController::class, 'salary_monitoring_index'])->name('salary-monitoring');
Route::post('/salary-monitoring-approve', [SalaryController::class, 'salary_monitoring_approve'])->name('salary-monitoring-approve');
// -------------------------------------------------------------------

// OvertimeController
// -------------------------------------------------------------------
Route::get('/overtime-approval-index', [OvertimeController::class, 'index'])->name('overtime-approval-index');
Route::post('/overtime-approval-store', [OvertimeController::class, 'store'])->name('overtime-approval-store');

Route::get('/overtime-summary-index', [OvertimeController::class, 'index_summary'])->name('overtime-summary-index');
Route::post('/overtime-summary-store', [OvertimeController::class, 'store_summary'])->name('overtime-summary-store');

Route::get('/overtime-master-index', [OvertimeController::class, 'overtime_master_index'])->name('overtime-master-index');
Route::post('/overtime-master-store', [OvertimeController::class, 'overtime_master_store'])->name('overtime-master-store');
Route::put('/overtime-master-update/{id}', [OvertimeController::class, 'overtime_master_update'])->name('overtime-master-update');
Route::delete('/overtime-master-destroy/{id}', [OvertimeController::class, 'overtime_master_destory'])->name('overtime-master-destroy');

Route::get('/overtime-limit-index', [OvertimeController::class, 'overtime_limit_index'])->name('overtime-limit-index');
Route::post('/overtime-limit-store', [OvertimeController::class, 'overtime_limit_store'])->name('overtime-limit-store');
// Route::get('/overtime-master-index', [OvertimeController::class, 'overtime_master_index'])->name('overtime-master-index');
// -------------------------------------------------------------------

// UserController
// -------------------------------------------------------------------
Route::get('/api-user', [UserController::class, 'getApiUser'])->name('api-user');
// -------------------------------------------------------------------
