<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationController;

// Routing welcome page
Route::get('/', [HomeController::class , 'welcome']);

// Routing login form
Route::get('login', [LoginController::class , 'showLoginForm']);
// Routing login and logut
Route::post('login', [LoginController::class , 'login'])
    ->name('login');
Route::post('logout', [LoginController::class , 'logout'])
    ->name('logout');

Route::get('dashboard', [HomeController::class , 'index'])
    ->name('home');
// Redirect home url to dashboard
Route::get('home', function ()
{
    return redirect('dashboard');
});

// Routing Office page
Route::get('office', [OfficeController::class , 'index'])
    ->name('office');
Route::get('office/new_office', [OfficeController::class , 'create'])
    ->name('office.create');
Route::post('office/new_office', [OfficeController::class , 'store'])
    ->name('office.store');
Route::get('office/edit_office/{id}', [OfficeController::class , 'edit'])
    ->name('office.edit');
Route::patch('office/edit_office', [OfficeController::class , 'update'])
    ->name('office.update');
Route::delete('office/delete', [OfficeController::class , 'delete'])
    ->name('office.delete');
Route::delete('office/truncate', [OfficeController::class , 'truncate'])
    ->name('office.truncate');

// Routing Asset page
Route::get('asset', [AssetController::class , 'index'])
    ->name('asset');
Route::get('asset/new_asset', [AssetController::class , 'create'])
    ->name('asset.create');
Route::post('asset/new_asset', [AssetController::class , 'store'])
    ->name('asset.store');
Route::get('asset/edit_asset/{id}', [AssetController::class , 'edit'])
    ->name('asset.edit');
Route::patch('asset/edit_asset', [AssetController::class , 'update'])
    ->name('asset.update');
Route::delete('asset/delete', [AssetController::class , 'delete'])
    ->name('asset.delete');
Route::post('asset/search', [AssetController::class , 'search'])
    ->name('asset.search');
Route::delete('asset/truncate', [AssetController::class , 'truncate'])
    ->name('asset.truncate');

// Routing Validation page
Route::get('validation', [ValidationController::class , 'index'])
    ->name('validation');
Route::get('validation/{office}/list', [ValidationController::class , 'list'])
    ->name('validation.list');
Route::post('validation/validate', [ValidationController::class , 'store'])
    ->name('validation.store');
Route::patch('validation/revalidate', [ValidationController::class , 'update'])
    ->name('validation.update');
Route::delete('validation/delete', [ValidationController::class , 'delete'])
    ->name('validation.delete');
Route::delete('validation/truncate', [ValidationController::class , 'truncate'])
    ->name('validation.truncate');

// Routing Transaction page
Route::get('transaction', [HistoryController::class , 'index'])
    ->name('history');
Route::get('transaction/new_transaction', [HistoryController::class , 'create'])
    ->name('history.create');
Route::post('transaction/new_transaction', [HistoryController::class , 'store'])
    ->name('history.store');
Route::get('transaction/edit_transaction/{id}', [HistoryController::class , 'edit'])
    ->name('history.edit');
Route::patch('transaction/edit_transaction', [HistoryController::class , 'update'])
    ->name('history.update');
Route::delete('transaction', [HistoryController::class , 'delete'])
    ->name('history.delete');
Route::get('transaction/detail/{unique}', [HistoryController::class , 'detail'])
    ->name('history.detail');
Route::get('transaction/{unique}/new_transaction', [HistoryController::class , 'detailCreate'])
    ->name('history.detail.create');
Route::post('transaction/{unique}/new_transaction', [HistoryController::class , 'detailStore'])
    ->name('history.detail.store');
Route::get('transaction/{unique}/edit_transaction/{id}', [HistoryController::class , 'detailEdit'])
    ->name('history.detail.edit');
Route::patch('transaction/{unique}/edit_transaction', [HistoryController::class , 'detailUpdate'])
    ->name('history.detail.update');
Route::delete('transaction/{unique}/{id}', [HistoryController::class , 'detailDelete'])
    ->name('history.detail.delete');

// Routing Person page
Route::get('person', [PersonController::class , 'index'])
    ->name('person');
Route::get('person/new_person', [PersonController::class , 'create'])
    ->name('person.create');
Route::post('person/new_person', [PersonController::class , 'store'])
    ->name('person.store');
Route::get('person/edit_person/{id}', [PersonController::class , 'edit'])
    ->name('person.edit');
Route::patch('person/edit_person', [PersonController::class , 'update'])
    ->name('person.update');
Route::delete('person/delete', [PersonController::class , 'delete'])
    ->name('person.delete');
Route::delete('person/truncate', [PersonController::class , 'truncate'])
    ->name('person.truncate');

// Routing Admin page
Route::get('admin', [UserController::class , 'index'])
    ->name('admin');
Route::get('admin/new_admin', [UserController::class , 'create'])
    ->name('admin.create');
Route::post('admin/new_admin', [UserController::class , 'store'])
    ->name('admin.store');
Route::get('admin/edit_admin/{id}', [UserController::class , 'edit'])
    ->name('admin.edit');
Route::patch('admin/edit_admin', [UserController::class , 'update'])
    ->name('admin.update');
Route::post('admin/edit_password', [UserController::class , 'editPassword'])
    ->name('admin.editPassword');
Route::patch('admin/edit_password', [UserController::class , 'updatePassword'])
    ->name('admin.updatePassword');
Route::delete('admin/delete', [UserController::class , 'delete'])
    ->name('admin.delete');

// Routing Profile page
Route::get('profile', [UserController::class , 'userProfile'])
    ->name('profile');
Route::get('profile/edit/{id}', [UserController::class , 'userProfileEdit'])
    ->name('profile.edit');
Route::patch('profile/edit', [UserController::class , 'userProfileUpdate'])
    ->name('profile.update');
Route::post('profile/password/edit', [UserController::class , 'passwordEdit'])
    ->name('password.edit');
Route::patch('profile/password/edit', [UserController::class , 'passwordUpdate'])
    ->name('password.update');

// Routing User page   
Route::get('user/activity', [UserController::class , 'userActivity'])
    ->name('activity');
Route::get('user/activity/detail/{id}', [UserController::class , 'getActivity'])
    ->name('activity.detail');
Route::post('user/activity/read_all', [UserController::class , 'readAll'])
    ->name('activity.read');
Route::delete('user/trucateActivity', [UserController::class , 'truncateActivity'])
    ->name('user.truncateActivity');

// Routing Setting page    
Route::get('settings', [HomeController::class , 'setting'])
    ->name('setting');
// Route::delete('settings/trucateActivity', [HomeController::class , 'truncateActivity'])
//     ->name('setting.truncateActivity');
// Route::delete('settings/truncateAll', [HomeController::class , 'truncateAll'])
//     ->name('setting.truncate');

// Routing Export file
Route::post('export/transaction', [HistoryController::class, 'export'])->name('export.transaction');
Route::post('export/validation', [ValidationController::class, 'export'])->name('export.validation');

// // Routing Import File
// Route::post('import/office', [OfficeController::class, 'import'])->name('import.office');
// Route::post('import/user', [PersonController::class, 'import'])->name('import.user');
// Route::post('import/asset', [AssetController::class, 'import'])->name('import.asset');
// Route::post('import/transaction', [HistoryController::class, 'import'])->name('import.transaction');

Route::get('download/office', [OfficeController::class, 'download'])->name('download.office');
Route::get('download/user', [PersonController::class, 'download'])->name('download.user');
Route::get('download/asset', [AssetController::class, 'download'])->name('download.asset');
Route::get('download/transaction', [HistoryController::class, 'download'])->name('download.transaction');