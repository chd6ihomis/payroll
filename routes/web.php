<?php

use App\Notification;
use Illuminate\Support\Facades\Auth;
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

/** Route::get('/', function () {
    return view('welcome');
})->name('welcome'); **/

Route::view('/', 'welcome')->name('welcome');


Route::group(['middleware' => 'auth'], function(){

    Route::resources([
        'employees' => 'EmployeeController',
        'payrolls' => 'PayrollController',
        'salaries' => 'SalaryController',
        'records' => 'RecordController',
        'notifications' => 'NotificationController',
        'announcements' => 'AnnouncementController'
    ]);

    Route::get('/filter-period-payroll', 'PayrollController@filter')->name('filter-payroll');
    Route::get('/filter-period-salary', 'SalaryController@filter')->name('filter-salary');
    Route::get('/filter-period-remittance', 'SalaryController@remittance')->name('filter-remittance');
    Route::post('/all-cos-payroll', 'PayrollController@showall')->name('showall-payrolls');
    Route::get('/all-cos-salaries', 'SalaryController@showall')->name('showall-salaries');
    Route::get('/all-cos-remittances', 'SalaryController@remittanceShowAll')->name('showall-remittances');
    Route::post('/printpayroll', 'PayrollController@printpayroll')->name('printpayroll');
	Route::get('/printobr/{period}/{type}', 'PayrollController@printobr')->name('printobr');
	Route::get('/printobr-conap/{period}/{type}', 'PayrollController@printobrConap')->name('printobr-conap');
	Route::get('/printdv-conap/{period}/{type}', 'PayrollController@printdvConap')->name('printdv-conap');
    Route::get('/printdv/{period}/{type}', 'PayrollController@printdv')->name('printdv');
    Route::post('/duplicatepayroll', 'PayrollController@duplicatepayroll')->name('duplicatepayroll');
    Route::get('/get-payroll/{id}/edit', 'PayrollController@getPayrollId');
    Route::post('/cashiercopy', 'PayrollController@cashiercopy')->name('cashiercopy');
    Route::get('/printsoa/{id}', 'SalaryController@printsoa')->name('printsoa');
    Route::get('/salaryincorrect/{id}/edit', 'SalaryController@salaryIncorrect');
    Route::get('/like/{id}', 'AnnouncementController@liked')->name('announcement.liked');
    Route::get('/post/{id}', 'AnnouncementController@pin')->name('post.pin');
    Route::get('/view-likers/{id}', 'AnnouncementController@likers')->name('likers.view');
    Route::get('/view-recent/{id}', 'AnnouncementController@recentPost')->name('recent.view');
    Route::get('/chats/{id}', 'NotificationController@chats');
    Route::post('/chats/', 'NotificationController@chatStore')->name('chats.store');

    Route::get('/change-password/{id}', 'HomeController@changepassword')->name('changepassword');
    Route::post('/confirmchange', 'HomeController@confirmchange')->name('confirmchange');

    Route::get('/refs-users', 'HomeController@refsUser')->name('refs.users');
    Route::get('/refs-users-reset/{id}', 'HomeController@resetpassword')->name('refs.reset');
	
	Route::get('/refs-fundsources', 'HomeController@refsFundsource')->name('refs.fundsources');
    Route::post('/refs-fundsources-add', 'HomeController@refsFundsourceAdd')->name('refs.fundsourcesAdd');
    Route::get('/refs-fundsources-edit/{id}', 'HomeController@refsFundsourceEdit')->name('refs.fundsourcesEdit');
    Route::get('/refs-fundsources-update/', 'HomeController@refsFundsourceUpdate')->name('refs.fundsourcesUpdate');
    Route::get('/refs-fundsources-delete/{id}', 'HomeController@refsFundsourceDelete')->name('refs.fundsourcesDelete');

    Route::get('/refs-signatories', 'HomeController@refsSignatory')->name('refs.signatories');
    Route::get('/refs-signatories-edit/{id}', 'HomeController@refsSignatoryEdit')->name('refs.signatoriesEdit');
    Route::get('/refs-signatories-update/', 'HomeController@refsSignatoryUpdate')->name('refs.signatoriesUpdate');
	
	Route::get('/report-utilization', 'SalaryController@reportUtilization')->name('report.utilization');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
