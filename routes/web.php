<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('upload', [PdfController::class, 'upload'])->name('upload');
Route::get('merge', [PdfController::class, 'merge'])->name('merge');
Route::get('filepond', [PdfController::class, 'filepond'])->name('filepond');
Route::get('pdf-to-text', [PdfController::class, 'pdfToText'])->name('pdf-to-text');
Route::get('upload-merge', [PdfController::class, 'uploadMerge'])->name('upload-merge');
Route::post('proses-merge', [PdfController::class, 'prosesMerge'])->name('proses-merge');
Route::post('proses-pdf-to-text', [PdfController::class, 'prosesPdfToText'])->name('proses-pdf-to-text');
Route::post('proses-merge-filepond', [PdfController::class, 'prosesMergeFilepond'])->name('proses-merge-filepond');
Route::post('proses-upload', [PdfController::class, 'prosesUpload'])->name('proses-upload');