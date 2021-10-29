<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BpController;
use App\Http\Controllers\CitiController;
use App\Http\Controllers\HdfcController;
use App\Http\Controllers\SbiController;
use App\Http\Controllers\ScbController;
use App\Http\Controllers\TcController;
use App\Http\Controllers\TlController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('reset-password/{id}', [AuthController::class, 'reset_password']);
Route::post('change-password', [AuthController::class, 'change_password']);


Route::post('update', [UserController::class, 'update'])->name('update');
Route::post('save-id-proof', [UserController::class, 'save_id_proof']);
Route::post('save-image', [UserController::class, 'save_image'])->name('save_image');
Route::post('delete', [UserController::class, 'delete'])->name('delete');

Route::middleware('auth:api')->group(function () {
});
Route::get('user/{userId}/detail', [UserController::class, 'show']);

Route::get('get-tl-list/{id}', [TlController::class, 'showTl']);
Route::get('get-tc-list/{id}', [TcController::class, 'showTc']);
Route::get('get-tl-list-add', [TcController::class, 'showTlToAdd']);
Route::post('add-leader', [TcController::class, 'add_leader']);
Route::get('get-bp-list/{id}', [BpController::class, 'showBp']);
// lead entry
// scb
Route::post('lead-entry-scb', [ScbController::class, 'lead_entry_scb']);
Route::get('get-scb-summary-tc/{id}/{s_date}/{e_date}', [ScbController::class, 'showScbSummaryTc']);
Route::get('get-scb-summary-tl/{id}/{s_date}/{e_date}', [ScbController::class, 'showScbSummaryTl']);
Route::get('get-scb-summary-bm/{id}/{s_date}/{e_date}', [ScbController::class, 'showScbSummaryBm']);
Route::get('get-scb-data/{id}/{s_date}/{e_date}', [ScbController::class, 'showScbData']);
Route::get('get-lead-scb/{lead_id}', [ScbController::class, 'getLeadScb']);
Route::post('save-file-scb', [ScbController::class, 'save_file_scb'])->name('save_file_scb');
Route::post('delete-scb-lead', [ScbController::class, 'delete_scb_lead'])->name('delete.scb.lead');

// sbi
Route::post('lead-check-sbi', [SbiController::class, 'lead_check_sbi']);
Route::post('lead-entry-sbi', [SbiController::class, 'lead_entry_sbi']);
Route::post('bm-pan-sbi', [SbiController::class, 'bm_check_pan']);
Route::post('app-code-sbi', [SbiController::class, 'app_code_sbi']);
Route::post('lead-status-sbi', [SbiController::class, 'lead_status_sbi']);
Route::get('get-sbi-summary-tc/{id}/{s_date}/{e_date}', [SbiController::class, 'showSbiSummaryTc']);
Route::get('get-sbi-summary-tl/{id}/{s_date}/{e_date}', [SbiController::class, 'showSbiSummaryTl']);
Route::get('get-sbi-summary-bm/{id}/{s_date}/{e_date}', [SbiController::class, 'showSbiSummaryBm']);
Route::get('get-sbi-data/{id}/{s_date}/{e_date}', [SbiController::class, 'showSbiData']);
Route::get('get-sbi-duplicate/{id}/{s_date}/{e_date}', [SbiController::class, 'showSbiDuplicate']);
Route::get('get-lead-sbi/{lead_id}', [SbiController::class, 'getLeadSbi']);
Route::post('tl-approve-sbi', [SbiController::class, 'tl_appropve_sbi']);
Route::post('tl-disapprove-sbi', [SbiController::class, 'tl_disappropve_sbi']);
Route::post('save-file-sbi', [SbiController::class, 'save_file_sbi'])->name('save_file_sbi');
Route::post('delete-sbi-lead', [SbiController::class, 'delete_sbi_lead'])->name('delete.sbi.lead');

// citi
Route::post('lead-entry-citi', [CitiController::class, 'lead_entry_citi']);
Route::get('get-citi-summary/{id}', [CitiController::class, 'showCitiSummary']);
Route::get('get-citi-data/{id}', [CitiController::class, 'showCitiData']);
Route::get('get-lead-citi/{lead_id}', [CitiController::class, 'getLeadCiti']);
Route::get('get-citi-summary-tc/{id}/{s_date}/{e_date}', [CitiController::class, 'showCitiSummaryTc']);
Route::get('get-citi-summary-tl/{id}/{s_date}/{e_date}', [CitiController::class, 'showCitiSummaryTl']);
Route::get('get-citi-summary-bm/{id}/{s_date}/{e_date}', [CitiController::class, 'showCitiSummaryBm']);
Route::get('get-citi-data/{id}/{s_date}/{e_date}', [CitiController::class, 'showCitiData']);
Route::get('get-citi-duplicate/{id}/{s_date}/{e_date}', [CitiController::class, 'showCitiDuplicate']);
Route::post('save-file-citi', [CitiController::class, 'save_file_citi'])->name('save_file_citi');
Route::post('delete-citi-lead', [CitiController::class, 'delete_citi_lead'])->name('delete.citi.lead');

// hdfc
Route::post('lead-entry-hdfc', [HdfcController::class, 'lead_entry_hdfc']);
Route::get('get-hdfc-summary/{id}', [HdfcController::class, 'showHdfcSummary']);
Route::get('get-hdfc-data/{id}', [HdfcController::class, 'showHdfcData']);
Route::get('get-lead-hdfc/{lead_id}', [HdfcController::class, 'getLeadHdfc']);
Route::get('get-hdfc-summary-tc/{id}/{s_date}/{e_date}', [HdfcController::class, 'showHdfcSummaryTc']);
Route::get('get-hdfc-summary-tl/{id}/{s_date}/{e_date}', [HdfcController::class, 'showHdfcSummaryTl']);
Route::get('get-hdfc-summary-bm/{id}/{s_date}/{e_date}', [HdfcController::class, 'showHdfcSummaryBm']);
Route::get('get-hdfc-data/{id}/{s_date}/{e_date}', [HdfcController::class, 'showHdfcData']);
Route::get('get-hdfc-duplicate/{id}/{s_date}/{e_date}', [HdfcController::class, 'showHdfcDuplicate']);
Route::post('save-file-hdfc', [HdfcController::class, 'save_file_hdfc']);
Route::post('delete-hdfc-lead', [HdfcController::class, 'delete_hdfc_lead']);


