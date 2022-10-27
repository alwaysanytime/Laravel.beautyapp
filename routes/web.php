<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

//Register
Route::post('/getCountry', 'Backend\ComboController@getCountryList')->name('getCountry');
Route::post('/StaffClientRegister', 'Auth\UserController@StaffClientRegister')->name('StaffClientRegister');

//reset password
Route::post('/reset_password', 'Auth\UserController@resetPassword')->name('reset_password');
Route::post('/password_update', 'Auth\UserController@resetPasswordUpdate')->name('password_update');

//SMS Reminder
Route::get('/SMSCronJob', 'Backend\SMSCronController@SendSMS')->name('backend.SMSCronJob');


Route::prefix('backend')->group(function () {

    //Not Found Page
    Route::get('/notfound', 'HomeController@notFoundPage')->name('backend.notfound')->middleware('auth');

    //Dashboard Page
/*    
    Route::get('/dashboard', 'Backend\DashboardController@getDashboardData')->name('backend.dashboard')->middleware('auth');
    Route::post('/getTotalProjects', 'Backend\DashboardController@getTotalProjects')->name('backend.getTotalProjects')->middleware('auth');
    Route::post('/getTotalTasks', 'Backend\DashboardController@getTotalTasks')->name('backend.getTotalTasks')->middleware('auth');
    Route::post('/getStaffStatus', 'Backend\DashboardController@getStaffStatus')->name('backend.getStaffStatus')->middleware('auth');
    Route::post('/getProjectList', 'Backend\DashboardController@getProjectList')->name('backend.getProjectList')->middleware('auth');
    Route::post('/getDashboardClientList', 'Backend\DashboardController@getDashboardClientList')->name('backend.getDashboardClientList')->middleware('auth');
*/
    //Task Board Page
    Route::get('/task-board/{id}', 'Backend\TaskBoardController@getTaskBoardPageLoad')->name('backend.task-board')->middleware('auth');
    Route::post('/getProjectInfo', 'Backend\TaskBoardController@getProjectInfo')->name('backend.getProjectInfo')->middleware('auth');
    Route::post('/getTaskBoardData', 'Backend\TaskBoardController@getTaskBoardData')->name('backend.getTaskBoardData')->middleware('auth');
    Route::post('/saveTaskBoardData', 'Backend\TaskBoardController@saveTaskBoardData')->name('backend.saveTaskBoardData')->middleware('auth');
    Route::post('/getTaskBoardById', 'Backend\TaskBoardController@getTaskBoardById')->name('backend.getTaskBoardById')->middleware('auth');
    Route::post('/deleteTaskBoard', 'Backend\TaskBoardController@deleteTaskBoard')->name('backend.deleteTaskBoard')->middleware('auth');
    Route::post('/saveTaskData', 'Backend\TaskBoardController@saveTaskData')->name('backend.saveTaskData')->middleware('auth');
    Route::post('/getTaskById', 'Backend\TaskBoardController@getTaskById')->name('backend.getTaskById')->middleware('auth');
    Route::post('/deleteTask', 'Backend\TaskBoardController@deleteTask')->name('backend.deleteTask')->middleware('auth');
    Route::post('/updateTaskStatusData', 'Backend\TaskBoardController@updateTaskStatusData')->name('backend.updateTaskStatusData')->middleware('auth');
    Route::post('/updateTaskMove', 'Backend\TaskBoardController@updateTaskMove')->name('backend.updateTaskMove')->middleware('auth');
    Route::post('/getInviteTaskData', 'Backend\TaskBoardController@getInviteTaskData')->name('backend.getInviteTaskData')->middleware('auth');
    Route::post('/getInviteStaff', 'Backend\TaskBoardController@getInviteStaff')->name('backend.getInviteStaff')->middleware('auth');
    Route::post('/insertInviteTaskData', 'Backend\TaskBoardController@insertInviteTaskData')->name('backend.insertInviteTaskData')->middleware('auth');
    Route::post('/deleteInviteTask', 'Backend\TaskBoardController@deleteInviteTask')->name('backend.deleteInviteTask')->middleware('auth');
    Route::post('/getActiveStaffinProjects', 'Backend\TaskBoardController@getActiveStaffinProjects')->name('backend.getActiveStaffinProjects')->middleware('auth');
    Route::post('/getInviteProjectsData', 'Backend\TaskBoardController@getInviteProjectsData')->name('backend.getInviteProjectsData')->middleware('auth');
    Route::post('/getStaffbyProject', 'Backend\TaskBoardController@getStaffbyProject')->name('backend.getStaffbyProject')->middleware('auth');
    Route::post('/onTasklistSortable', 'Backend\TaskBoardController@onTasklistSortable')->name('backend.onTasklistSortable')->middleware('auth');
    Route::post('/onTaskGroupSortable', 'Backend\TaskBoardController@onTaskGroupSortable')->name('backend.onTaskGroupSortable')->middleware('auth');
    Route::post('/getCommentsData', 'Backend\TaskBoardController@getCommentsData')->name('backend.getCommentsData')->middleware('auth');
    Route::post('/insertUpdateComments', 'Backend\TaskBoardController@insertUpdateComments')->name('backend.insertUpdateComments')->middleware('auth');
    Route::post('/updateComments', 'Backend\TaskBoardController@updateComments')->name('backend.updateComments')->middleware('auth');
    Route::post('/deleteComment', 'Backend\TaskBoardController@deleteComment')->name('backend.deleteComment')->middleware('auth');
    Route::post('/addAttachment', 'Backend\TaskBoardController@addAttachment')->name('backend.addAttachment')->middleware('auth');
    Route::post('/updateAttachTitle', 'Backend\TaskBoardController@updateAttachTitle')->name('backend.updateAttachTitle')->middleware('auth');
    Route::post('/deleteAttach', 'Backend\TaskBoardController@deleteAttach')->name('backend.deleteAttach')->middleware('auth');

    //Project Page
    Route::get('/project', 'Backend\ProjectController@getProjectPageLoad')->name('backend.project')->middleware('auth');
    Route::post('/getProjectData', 'Backend\ProjectController@getProjectData')->name('backend.getProjectData')->middleware('auth');
    Route::post('/saveProjectData', 'Backend\ProjectController@saveProjectData')->name('backend.saveProjectData')->middleware('auth');
    Route::post('/getProjectById', 'Backend\ProjectController@getProjectById')->name('backend.getProjectById')->middleware('auth');
    Route::post('/deleteProject', 'Backend\ProjectController@deleteProject')->name('backend.deleteProject')->middleware('auth');
    Route::post('/getInvitedStaff', 'Backend\ProjectController@getInvitedStaff')->name('backend.getInvitedStaff')->middleware('auth');
    Route::post('/getStaffList', 'Backend\ProjectController@getStaffList')->name('backend.getStaffList')->middleware('auth');
    Route::post('/saveInviteData', 'Backend\ProjectController@saveInviteData')->name('backend.saveInviteData')->middleware('auth');
    Route::post('/InviteActiveInactive', 'Backend\ProjectController@InviteActiveInactive')->name('backend.InviteActiveInactive')->middleware('auth');
    Route::post('/deleteInviteProject', 'Backend\ProjectController@deleteInviteProject')->name('backend.deleteInviteProject')->middleware('auth');

    //Profile Page
    Route::get('/profile', 'Backend\ProfileController@getProfilePageLoad')->name('backend.profile')->middleware('auth');
    Route::post('/getProfileData', 'Backend\ProfileController@getProfileData')->name('backend.getProfileData')->middleware('auth');
    Route::post('/saveProfileData', 'Backend\ProfileController@saveProfileData')->name('backend.saveProfileData')->middleware('auth');

    //Client Page
    Route::get('/client', 'Backend\ClientController@getClientPageLoad')->name('backend.client')->middleware('auth');
    Route::post('/getClientData', 'Backend\ClientController@getClientData')->name('backend.getClientData')->middleware('auth');
    Route::post('/saveClientData', 'Backend\ClientController@saveClientData')->name('backend.saveClientData')->middleware('auth');
    Route::post('/getClientById', 'Backend\ClientController@getClientById')->name('backend.getClientById')->middleware('auth');
    Route::post('/deleteClient', 'Backend\ClientController@deleteClient')->name('backend.deleteClient')->middleware('auth');

    //Staff Page
    /*
        Route::get('/staff', 'Backend\StaffController@getStaffPageLoad')->name('backend.staff')->middleware(['auth','is_admin']);
        Route::post('/getStaffData', 'Backend\StaffController@getStaffData')->name('backend.getStaffData')->middleware(['auth','is_admin']);
        Route::post('/saveStaffData', 'Backend\StaffController@saveStaffData')->name('backend.saveStaffData')->middleware(['auth','is_admin']);
        Route::post('/saveStaffData', 'Backend\StaffController@saveStaffData')->name('backend.saveStaffData')->middleware(['auth','is_admin']);
        Route::post('/getStaffById', 'Backend\StaffController@getStaffById')->name('backend.getStaffById')->middleware(['auth','is_admin']);
        Route::post('/deleteStaff', 'Backend\StaffController@deleteStaff')->name('backend.deleteStaff')->middleware(['auth','is_admin']);

        //Meeting Page
        Route::get('/upcoming-meeting', 'Backend\ZoomMeetingController@getUpcomingMeetingData')->name('backend.upcoming-meeting')->middleware(['auth','is_admin']);
        Route::get('/live-meeting', 'Backend\ZoomMeetingController@getLiveMeetingData')->name('backend.live-meeting')->middleware(['auth','is_admin']);
        Route::get('/previous-meeting', 'Backend\ZoomMeetingController@getPreviousMeetingData')->name('backend.previous-meeting')->middleware(['auth','is_admin']);
        Route::get('/zoom-settings', 'Backend\ZoomMeetingController@getZoomSettingsData')->name('backend.zoom-settings')->middleware(['auth','is_admin']);
        Route::post('/getUpcomingMeetingDataLoad', 'Backend\ZoomMeetingController@getUpcomingMeetingDataLoad')->name('backend.getUpcomingMeetingDataLoad')->middleware(['auth','is_admin']);
        Route::post('/getLiveMeetingDataLoad', 'Backend\ZoomMeetingController@getLiveMeetingDataLoad')->name('backend.getLiveMeetingDataLoad')->middleware(['auth','is_admin']);
        Route::post('/getPreviousMeetingDataLoad', 'Backend\ZoomMeetingController@getPreviousMeetingDataLoad')->name('backend.getPreviousMeetingDataLoad')->middleware(['auth','is_admin']);
        Route::post('/CreateMeeting', 'Backend\ZoomMeetingController@CreateMeeting')->name('backend.CreateMeeting')->middleware(['auth','is_admin']);
        Route::post('/getMeetingDetails', 'Backend\ZoomMeetingController@getMeetingDetails')->name('backend.getMeetingDetails')->middleware(['auth','is_admin']);
        Route::post('/deleteMeeting', 'Backend\ZoomMeetingController@deleteMeeting')->name('backend.deleteMeeting')->middleware(['auth','is_admin']);
        Route::post('/getStaffClientList', 'Backend\ZoomMeetingController@getStaffClientList')->name('backend.getStaffClientList')->middleware(['auth','is_admin']);
        Route::post('/getMeetingInvitationStaff', 'Backend\ZoomMeetingController@getMeetingInvitationStaff')->name('backend.getMeetingInvitationStaff')->middleware(['auth','is_admin']);
        Route::post('/insertMeetingInvitationData', 'Backend\ZoomMeetingController@insertMeetingInvitationData')->name('backend.insertMeetingInvitationData')->middleware(['auth','is_admin']);
        Route::post('/deleteMeetingInvitation', 'Backend\ZoomMeetingController@deleteMeetingInvitation')->name('backend.deleteMeetingInvitation')->middleware(['auth','is_admin']);
        Route::post('/SaveZoomSettings', 'Backend\ZoomMeetingController@SaveZoomSettings')->name('backend.SaveZoomSettings')->middleware(['auth','is_admin']);

        //languages Page
        Route::get('/languages', 'Backend\LangaugeController@getLanguagePageLoad')->name('backend.languages')->middleware(['auth','is_admin']);
        Route::post('/getLanguagesData', 'Backend\LangaugeController@getLanguagesData')->name('backend.getLanguagesData')->middleware(['auth','is_admin']);
        Route::post('/saveLanguagesData', 'Backend\LangaugeController@saveLanguagesData')->name('backend.saveLanguagesData')->middleware(['auth','is_admin']);
        Route::post('/getLanguageById', 'Backend\LangaugeController@getLanguageById')->name('backend.getLanguageById')->middleware(['auth','is_admin']);
        Route::post('/deleteLanguage', 'Backend\LangaugeController@deleteLanguage')->name('backend.deleteLanguage')->middleware(['auth','is_admin']);
        Route::get('/language-keywords', 'Backend\LangaugeController@getLanguageKeywordsPageLoad')->name('backend.language-keywords')->middleware(['auth','is_admin']);
        Route::post('/getLanguageKeywordsData', 'Backend\LangaugeController@getLanguageKeywordsData')->name('backend.getLanguageKeywordsData')->middleware(['auth','is_admin']);
        Route::post('/saveLanguageKeywordsData', 'Backend\LangaugeController@saveLanguageKeywordsData')->name('backend.saveLanguageKeywordsData')->middleware(['auth','is_admin']);
        Route::post('/getLanguageKeywordsById', 'Backend\LangaugeController@getLanguageKeywordsById')->name('backend.getLanguageKeywordsById')->middleware(['auth','is_admin']);
        Route::post('/getLanguageCombo', 'Backend\LangaugeController@getLanguageCombo')->name('backend.getLanguageCombo')->middleware(['auth','is_admin']);
        Route::post('/deleteLanguageKeywords', 'Backend\LangaugeController@deleteLanguageKeywords')->name('backend.deleteLanguageKeywords')->middleware(['auth','is_admin']);

        //Settings Page
        Route::get('/settings', 'Backend\SettingsController@getSettingsData')->name('backend.settings')->middleware(['auth','is_admin']);
        Route::post('/getGlobalSettingData', 'Backend\SettingsController@getGlobalSettingData')->name('backend.getGlobalSettingData')->middleware(['auth','is_admin']);
        Route::post('/globalSettingUpdate', 'Backend\SettingsController@globalSettingUpdate')->name('backend.globalSettingUpdate')->middleware(['auth','is_admin']);
        Route::post('/GoogleRecaptchaUpdate', 'Backend\SettingsController@GoogleRecaptchaUpdate')->name('backend.GoogleRecaptchaUpdate')->middleware(['auth','is_admin']);
        Route::post('/getMailSettingData', 'Backend\SettingsController@getMailSettingData')->name('backend.getMailSettingData')->middleware(['auth','is_admin']);
        Route::post('/MailSettingUpdate', 'Backend\SettingsController@MailSettingUpdate')->name('backend.MailSettingUpdate')->middleware(['auth','is_admin']);
        Route::post('/StripeUpdate', 'Backend\SettingsController@StripeUpdate')->name('backend.StripeUpdate')->middleware(['auth','is_admin']);
        Route::post('/PurchaseCodeUpdate', 'Backend\SettingsController@PurchaseCodeUpdate')->name('backend.PurchaseCodeUpdate')->middleware(['auth','is_admin']);
        Route::post('/getPcodeData', 'Backend\SettingsController@getPcodeData')->name('backend.getPcodeData')->middleware(['auth','is_admin']);
        Route::post('/deletePcode', 'Backend\SettingsController@deletePcode')->name('backend.deletePcode')->middleware(['auth','is_admin']);

        //Milestone Page
        Route::get('/milestones/{id}', 'Backend\MilestoneController@getMilestonesPageLoad')->name('backend.milestones')->middleware('auth');
        Route::post('/getMilestoneData', 'Backend\MilestoneController@getMilestoneData')->name('backend.getMilestoneData')->middleware('auth');
        Route::post('/saveMilestoneData', 'Backend\MilestoneController@saveMilestoneData')->name('backend.saveMilestoneData')->middleware('auth');
        Route::post('/getMilestoneById', 'Backend\MilestoneController@getMilestoneById')->name('backend.getMilestoneById')->middleware('auth');
        Route::post('/deleteMilestone', 'Backend\MilestoneController@deleteMilestone')->name('backend.deleteMilestone')->middleware('auth');
        Route::post('/getProjectName', 'Backend\MilestoneController@getProjectName')->name('backend.getProjectName')->middleware('auth');
        Route::post('/getClientInfo', 'Backend\MilestoneController@getClientInfo')->name('backend.getClientInfo')->middleware('auth');
        Route::post('/getInvoice', 'Backend\MilestoneController@getInvoice')->name('backend.getInvoice')->middleware('auth');
        Route::get('/invoice-pdf/{id}', 'Backend\MilestoneController@getInvoicePdf')->name('backend.invoice-pdf')->middleware('auth');
    */
    //All File Upload
    Route::get('/Files', 'Backend\UploadController@FileUpload')->name('backend.FileUpload')->middleware('auth');
    Route::post('/FileUpload', 'Backend\UploadController@FileUpload')->name('backend.FileUpload')->middleware('auth');
    Route::post('/attachmentUpload', 'Backend\UploadController@attachmentUpload')->name('backend.attachmentUpload')->middleware('auth');

    /*************************/

    // FILE UPLOAD PAGE
    Route::get('/fileuploadtest', 'Backend\UploadFileController@FileUploadLoad')->name('backend.FileUploadLoad')->middleware('auth');
    Route::post('/fileuploadtest', 'Backend\UploadFileController@FileUpload')->name('backend.FileUploaded')->middleware('auth');
    Route::get('/fileuploadtest/{id}', 'Backend\UploadFileController@deleteImage')->name('backend.FileDeleted')->middleware('auth');;

    /*************************/

    // CONTRACT PAGE
    Route::get('/contract', 'Backend\ContractController@getContractPageLoad')->name('backend.Contract')->middleware('auth');
    Route::post('/contract', 'Backend\ContractController@getNewContract')->name('backend.NewContract')->middleware('auth');
    Route::get('/contract-pdf/{pdffile}', 'Backend\ContractController@getPdfContract')->name('backend.contract-pdf')->middleware('auth');
//    Route::post('/contract', 'Backend\ContractController@getPdfPageLoad')->name('backend.contractpdf')->middleware('auth');
//    Route::post('/contract', 'Backend\ContractController@getPdfPageLoad')->name('backend.contractpdf')->middleware('auth');
//    Route::post('/getContract', 'Backend\ContractController@getContractData')->name('backend.getContractData');
//    Route::post('/saveContractData', 'Backend\ContractController@saveContractData')->name('backend.saveContractData');
//    Route::post('/getContractById', 'Backend\ContractController@getContractById')->name('backend.getContractById');
//    Route::post('/deleteContract', 'Backend\ContractController@deleteContract')->name('backend.deleteContract');

    /*************************/


    //DASHBOARD APPOINTMENT PAGE
    Route::get('/dashboard', 'Backend\DashboardAppointmentController@getTableAppointments')->name('backend.dashboard')->middleware('auth');

    //CALENDAR
    Route::get('/calendar', 'Backend\CalendarController@getCalendarPage')->name('backend.calendar')->middleware('auth');

    //All Combo
    Route::post('/getCountryList', 'Backend\ComboController@getCountryList')->name('backend.getCountryList')->middleware('auth');
    Route::post('/getUserActivesList', 'Backend\ComboController@getUserActivesList')->name('backend.getUserActivesList')->middleware('auth');
    Route::post('/getTimezoneList', 'Backend\ComboController@getTimezoneList')->name('backend.getTimezoneList')->middleware('auth');
    Route::post('/getMonthList', 'Backend\ComboController@getMonthList')->name('backend.getMonthList')->middleware('auth');
    Route::post('/getYearList', 'Backend\ComboController@getYearList')->name('backend.getYearList')->middleware('auth');
    Route::post('/getLanguageList', 'Backend\ComboController@getLanguageList')->name('backend.getLanguageList')->middleware('auth');
    Route::post('/getPaymentStatusList', 'Backend\ComboController@getPaymentStatusList')->name('backend.getPaymentStatusList')->middleware('auth');
    Route::post('/getUserRolesList', 'Backend\ComboController@getUserRolesList')->name('backend.getUserRolesList')->middleware('auth');
    Route::post('/getClientList', 'Backend\ComboController@getClientList')->name('backend.getClientList')->middleware('auth');
    Route::post('/getStatusList', 'Backend\ComboController@getStatusList')->name('backend.getStatusList')->middleware('auth');
    Route::post('/getTaskGroup', 'Backend\ComboController@getTaskGroup')->name('backend.getTaskGroup')->middleware('auth');
    Route::post('/getPaymentMethodList', 'Backend\ComboController@getPaymentMethodList')->name('backend.getPaymentMethodList')->middleware('auth');

    //Global
    Route::post('/userActive', 'Backend\GlobalController@userActive')->name('backend.userActive')->middleware('auth');
    Route::post('/calendar-details', 'Backend\CalendarController@showAppointmentsDetail')->name('backend.calendarDetails')->middleware('auth');
    Route::post('/updateAppointment', 'Backend\CalendarController@updateAppointment')->name('backend.updateAppointment')->middleware('auth');
    Route::post('/updateAgreement', 'Backend\CalendarController@updateAgreement')->name('backend.updateAgreement')->middleware('auth');

    //Chatting Page
    /*
        Route::get('/chat', 'Backend\ChatController@getChatPageLoad')->name('backend.chat')->middleware(['auth']);
        Route::post('/getUserList', 'Backend\ChatController@getUserList')->name('backend.getUserList')->middleware(['auth']);
        Route::post('/getUserById', 'Backend\ChatController@getUserById')->name('backend.getUserById')->middleware(['auth']);
        Route::post('/SaveMessage', 'Backend\ChatController@SaveMessage')->name('backend.SaveMessage')->middleware(['auth']);
        Route::post('/SaveFile', 'Backend\ChatController@SaveFile')->name('backend.SaveFile')->middleware(['auth']);
        Route::post('/getMessageList', 'Backend\ChatController@getMessageList')->name('backend.getMessageList')->middleware(['auth']);
        Route::post('/deleteMessageById', 'Backend\ChatController@deleteMessageById')->name('backend.deleteMessageById')->middleware(['auth']);
        Route::post('/editMessageById', 'Backend\ChatController@editMessageById')->name('backend.editMessageById')->middleware(['auth']);
        Route::post('/MessageSeenSave', 'Backend\ChatController@MessageSeenSave')->name('backend.MessageSeenSave')->middleware(['auth']);
    */
});
