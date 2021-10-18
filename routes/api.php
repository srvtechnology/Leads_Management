<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
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

Route::post('forgot-password', [AuthController::class, 'forgot_password']);
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
// lead entry
// scb
Route::post('lead-entry-scb', [LeadController::class, 'lead_entry_scb']);
// sbi
Route::post('lead-check-sbi', [LeadController::class, 'lead_check_sbi']);
Route::post('lead-entry-sbi', [LeadController::class, 'lead_entry_sbi']);
Route::post('bm-pan-sbi', [LeadController::class, 'bm_check_pan']);
Route::post('app-code-sbi', [LeadController::class, 'app_code_sbi']);
Route::post('lead-status-sbi', [LeadController::class, 'lead_status_sbi']);
Route::get('get-sbi-summary/{id}', [LeadController::class, 'showSbiSummary']);
Route::get('get-sbi-data/{id}', [LeadController::class, 'showSbiData']);
Route::get('get-sbi-duplicate/{id}', [LeadController::class, 'showSbiDuplicate']);
Route::get('get-lead-sbi/{lead_id}', [LeadController::class, 'getLeadSbi']);
Route::post('tl-approve-sbi', [LeadController::class, 'tl_appropve_sbi']);
Route::post('tl-disapprove-sbi', [LeadController::class, 'tl_disappropve_sbi']);
Route::post('save-file', [LeadController::class, 'save_file'])->name('save_file');
Route::get('get-scb-summary', [LeadController::class, 'showScbSummary']);
Route::get('get-scb-data', [LeadController::class, 'showScbData']);
Route::get('get-lead-scb/{lead_id}', [LeadController::class, 'getLeadScb']);
Route::post('save-file-scb', [LeadController::class, 'save_file_scb'])->name('save_file_scb');
