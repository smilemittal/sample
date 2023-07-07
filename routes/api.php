<?php

use App\Http\Controllers\Api\ActivityLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\XmeAreaController;
use App\Http\Controllers\Api\ReviewModuleController;
use App\Http\Controllers\Api\ReviewCompanyController;
use App\Http\Controllers\Api\DirectoryController;
use App\Http\Controllers\Api\ManageFormController;
use App\Http\Controllers\Api\UpdatedModuleHistoryController;
use App\Http\Controllers\Api\TrainingHistoryController;
use App\Http\Controllers\Api\LearningPathController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\EmailTemplateController;
use App\Http\Controllers\Api\EmailSettingController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\S3UploadController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\DashBoardCounterController;


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

/**login routes */
Route::post('/login', [LoginController::class, 'login']);
Route::post('/company-register', [LoginController::class, 'companyRegister']);
/**invite member */
Route::get('/validate-token/{id}', [MemberController::class, 'validateToken']);
Route::post('/create-member', [MemberController::class, 'createMember']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-authorization', [CompanyController::class, 'getAuthorization'])->name('user.authorization');
    Route::get('initial-data', [CompanyController::class, 'initialData']);
    Route::get('get-feature-availability', [PlanController::class, 'getFeatureAvailability']);
     /**DashbaordCouters routes */
     Route::get('/company-dashboard-counters', [DashBoardCounterController::class, 'companyCounters']);
     Route::get('/admin-dashboard-counters', [DashBoardCounterController::class, 'AdminCounters']);
     Route::get('/member-dashboard-counters', [DashBoardCounterController::class, 'memberCounters']);

    /**company routes */
    Route::apiResource('/companies', CompanyController::class);
    Route::post('/company-action', [CompanyController::class, 'actionAfterConfirmation']);
    Route::delete('/company-logo-delete/{id}', [CompanyController::class, 'deleteImage']);
    Route::post('/company-action', [CompanyController::class, 'companyAction']);
    Route::post('/multiple-company-action', [CompanyController::class, 'multipleAction']);

    /**Members routes */
    Route::apiResource('/members', MemberController::class);
    Route::put('/update-member/{id}', [MemberController::class, 'updateMember']);
    Route::delete('/delete-member-image/{id}', [MemberController::class, 'deleteImage']);
    Route::get('/assign-form/{id}', [MemberController::class, 'getAssignForm']);
    Route::get('/pending-invites', [MemberController::class, 'pendingInvite']);
    Route::post('/assign-user-forms/{id}', [MemberController::class, 'assignFormToUser']);
    Route::get('/login-as-user/{id}', [MemberController::class, 'loginasUser']);
    Route::post('/assign-admin-previleges/{id}', [MemberController::class, 'assignBAPrivileges']);
    Route::post('/remove-admin-previleges/{id}', [MemberController::class, 'removeBAPrivileges']);
    Route::post('/member-action', [MemberController::class, 'memberAction']);
    Route::post('/multiple-member-action', [MemberController::class, 'multipleAction']);
    Route::post('/resend-invite/{id}', [MemberController::class, 'resendInvite']);
    Route::post('/delete-invite/{id}', [MemberController::class, 'deleteInvite']);

    /**xmeareas routes */
    Route::apiResource('/xme-area', XmeAreaController::class);
    /**form routes */
    Route::apiResource('/forms', ManageFormController::class);
    Route::put('/update-form-status/{id}', [ManageFormController::class, 'updateFormStatus']);
    Route::post('/validate-form', [ManageFormController::class, 'validateForm']);
    Route::post('/update-form/{id}', [ManageFormController::class, 'updateForm']);
    Route::get('/assigned-companies/{id}', [ManageFormController::class, 'assignCompany']);
    Route::post('/assigned-company-form', [ManageFormController::class, 'assignCompanyToForm']);
    Route::post('/form-action', [ManageFormController::class, 'formAction']);
    Route::post('/multiple-form-action', [ManageFormController::class, 'multipleAction']);
    Route::post('/pull-response/{id}', [ManageFormController::class, 'pullResponsesFor']);

    /**review form routes */
    Route::get('/review-forms', [ReviewModuleController::class, 'list']);
    Route::get('/assigned-forms', [ReviewModuleController::class, 'assignedModules']);
    Route::put('/update-xmeform/{id}', [ReviewModuleController::class, 'updateXmeAdminModule']);
    Route::put('/update-review-date/{id}', [ReviewModuleController::class, 'updateReviewDate']);
    Route::get('/new-assigned-modules', [ReviewModuleController::class, 'newAssignedModules']);
    Route::put('/move-to-library/{id}', [ReviewModuleController::class, 'moveToLibrary']);
    Route::get('/review-history', [ReviewModuleController::class, 'reviewHistory']);
    /**company form upadte routes */
    Route::get('/review-company-forms', [ReviewCompanyController::class, 'list']);
    Route::put('/updated-company-form/{id}', [ReviewCompanyController::class, 'updateCompanyModule']);
    Route::get('/review-form-comments/{id}', [ReviewCompanyController::class, 'reviewCommentHistory']);

    /**Training History */
    Route::get('/upcoming-trainings', [TrainingHistoryController::class, 'upComingTraining']);
    Route::get('/completed-trainings', [TrainingHistoryController::class, 'completedTraining']);
    Route::get('/view-training-history/{id}', [TrainingHistoryController::class, 'viewTrainingHistory']);
    Route::get('/learning-path-trainings', [TrainingHistoryController::class, 'learningPathTraining']);
    Route::post('/completed-training', [TrainingHistoryController::class, 'completeTraining']);

    /**Learning Path */
    Route::get('/learning-paths', [LearningPathController::class, 'list']);
    Route::get('/edit-learning-path/{id}', [LearningPathController::class, 'edit']);
    Route::post('/update-learning-path/{id}', [LearningPathController::class, 'update']);
    Route::get('/learning-path-module/{id}', [LearningPathController::class, 'getLearningPathModules']);
    Route::get('/learning-path-members/{id}', [LearningPathController::class, 'getLearningPathMembers']);
    Route::post('/create-learning-path', [LearningPathController::class, 'createLearningPath']);
    Route::get('/assign-learning-modules/{id}', [LearningPathController::class, 'assignLearningModule']);
    Route::get('/completed-learning-modules/{id}', [LearningPathController::class, 'completedLearningpath']);
    Route::get('/learning-path-pending-members/{id}', [LearningPathController::class, 'learningPathPendingMembers']);
    Route::get('/learning-path-pending-modules/{id}', [LearningPathController::class, 'learningPathPendingModules']);
    Route::post('/add-learningpath-member/{id}', [LearningPathController::class, 'addMemberToLearningPath']);
    Route::post('/add-learningpath-module/{id}', [LearningPathController::class, 'addModuleToLearningPath']);
    Route::put('/lock-learning-path/{id}', [LearningPathController::class, 'lockLearningPath']);
    Route::post('/update-schedule-date/{id}', [LearningPathController::class, 'updateScheduleDate']);
    Route::post('/duplicate-learning-path/{id}', [LearningPathController::class, 'duplicateLearningPath']);
    Route::post('/update-learning-module-status/{id}', [LearningPathController::class, 'updateLearningModuleStatus']);
    Route::post('/remove-learning-Path-memeber/{id}', [LearningPathController::class, 'removeLearningPathMember']);
    Route::post('/remove-learning-Path-module/{id}', [LearningPathController::class, 'removeLearningPathModule']);
    Route::get('/learning-path-module-history/{id}', [LearningPathController::class, 'learningPathModuleHistory']);
    Route::post('/learning-path-action', [LearningPathController::class, 'learningPathAction']);
    Route::post('/multiple-learning-action', [LearningPathController::class, 'multipleAction']);
    Route::post('/update-learning-path-module-order/{id}', [LearningPathController::class, 'updateOrderAction']);

    /**Directory */
    Route::apiResource('/directories', DirectoryController::class);
    Route::post('/update-directory/{id}', [DirectoryController::class, 'updateDirectory']);
    Route::get('/pending-directory-modules/{id}', [DirectoryController::class, 'pendingDirectoryModule']);
    Route::post('/add-directory-module/{id}', [DirectoryController::class, 'addModulesToDirectory']);
    Route::get('/directory-modules/{id}', [DirectoryController::class, 'directoryModules']);
    Route::delete('/delete-directory/{id}', [DirectoryController::class, 'deleteDirectory'])->name('delete-directory');
    Route::post('/delete-directory-module/{id}', [DirectoryController::class, 'deleteDirectoryModule'])
    ->name('delete-directory-module');

    /**Library*/
    Route::get('/library', [LibraryController::class, 'list']);
    Route::any('/form/responses', [LibraryController::class, 'getResponses']);
    Route::post('/update-order-action', [LibraryController::class, 'updateOrderAction']);



    Route::put('/archived-module/{id}', [LibraryController::class, 'archivedModule']);
    Route::put('/unarchived-module/{id}', [LibraryController::class, 'unArchivedModule']);
    /**Menus*/
    Route::get('/menus', [MenuItemController::class, 'index']);
    /**Group*/
    Route::get('/groups', [GroupController::class, 'list']);
    Route::post('/create-group', [GroupController::class, 'create']);
    Route::get('/edit-group/{id}', [GroupController::class, 'edit']);
    Route::post('/update-group/{id}', [GroupController::class, 'update']);
    Route::get('/group-members/{id}', [GroupController::class, 'groupMembers']);
    Route::get('/group-modules/{id}', [GroupController::class, 'groupModules']);
    Route::get('/group-pending-members/{id}', [GroupController::class, 'groupPendingMembers']);
    Route::get('/group-pending-modules/{id}', [GroupController::class, 'groupPendingModules']);
    Route::post('/add-group-member/{id}', [GroupController::class, 'addMemberToGroup']);
    Route::post('/add-group-module/{id}', [GroupController::class, 'addModuleToGroup']);
    Route::post('/group-action', [GroupController::class, 'groupAction']);
    Route::post('/multiple-group-action', [GroupController::class, 'multipleAction']);
    Route::post('/delete-group-member/{id}', [GroupController::class, 'deleteGroupMember']);
    Route::post('/delete-group-module/{id}', [GroupController::class, 'deleteGroupModule']);
    Route::post('/reassign-setting/{id}', [GroupController::class, 'saveReassignSettings']);
    Route::post('/remove-reassign-setting/{id}', [GroupController::class, 'removeAssignSetting']);
    Route::get('/group-form/{id}', [GroupController::class, 'groupForm']);
    Route::post('/group-schedule-date/{id}', [GroupController::class, 'updateScheduleDate']);
    Route::post('/update-group-order-action', [GroupController::class, 'updateOrderAction']);

    /**subject routes */
    Route::post('/create-subject', [SubjectController::class, 'createSubject']);
    Route::post('/update-subject/{id}', [SubjectController::class, 'updateSubject']);
    Route::delete('/delete-attachment/{id}', [SubjectController::class, 'deleteAttachement']);
    Route::get('/download-images/{id}', [SubjectController::class, 'downloadZip']);
    Route::get('/edit-identify/{id}', [SubjectController::class, 'editIdentify'])->name('app.get.identify');
    Route::apiResource('/subjects', SubjectController::class);
    Route::get('/pps-sections', [SubjectController::class, 'ppsSections'])->name('app.pps.section');
    Route::post('/subject-action', [SubjectController::class, 'subjectAction'])->name('app.subject.action');
    Route::post('/update-module/{id}', [SubjectController::class, 'updateModule']);
    Route::post('/delete-action', [SubjectController::class, 'deleteAction'])->name('app.delete.action');
    Route::post('/multiple-subject-action', [SubjectController::class, 'multipleAction']);
    Route::get('/subject-counters', [SubjectController::class, 'subjectCounters']);

    

    /**notification routes */
    Route::get('/notifications-list', [NotificationController::class, 'index']);
    Route::delete('delete-notification/{id}', [NotificationController::class, 'deleteNotification']);
    Route::get('unread-notification/{id}', [NotificationController::class, 'unMarked']);
    Route::get('marked-notification/{id}', [NotificationController::class, 'marked']);
    Route::delete('clear-notification', [NotificationController::class, 'clearNotification']);
    Route::get('marked-all-notification', [NotificationController::class, 'markedAll']);
    Route::get('unmarked-all-notification', [NotificationController::class, 'unMarkedAll']);
    Route::get('/notifications', [NotificationController::class, 'unreadNotification']);

    /**email templates routes */
    Route::get('/email-templates', [EmailTemplateController::class, 'index']);
    Route::get('email-templates/edit/{id}', [EmailTemplateController::class, 'show'])->name('app.email-template.edit');
    Route::post('email-templates/update/{id}', [EmailTemplateController::class, 'update'])
        ->name('app.email-template.update');


    /**email settings routes **/
    Route::get('email-settings', [EmailSettingController::class, 'emailSettings'])->name('app.email-settings.edit');
    Route::post('email-settings/update', [EmailSettingController::class, 'emailSettingsUpdate'])
    ->name('app.email-settings.update');
    Route::put('email-layout-settings/update', [EmailSettingController::class, 'emailLayoutSettingsUpdate'])
        ->name('app.email-layout-settings.update');
    Route::post('email-settings/test', [EmailSettingController::class, 'sendTestEmail'])
        ->name('email-settings.test');
       
        
    /**Activity Logs */
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('app.activity-logs');

    //Beta Program
    Route::post('beta-program/update', [MemberController::class, 'updateBetaAccess']);

    Route::get('profile', [MemberController::class, 'profile']);
    Route::get('company-profile', [CompanyController::class, 'profile']);
    Route::post('update-default-source', [SubscriptionController::class, 'updateDefaultSource'])
        ->name('update.default.source');
    Route::get('list-source', [SubscriptionController::class, 'listSource'])->name('list.source');
    Route::get('delete-source/{id}', [SubscriptionController::class, 'deleteSource'])->name('delete.source');
    Route::get('get-intent-key', [SubscriptionController::class, 'getIntentKey'])->name('get.intent.key');


    /**plans routes */
    Route::apiResource('/plans', PlanController::class);

    Route::get('upcoming-invoice', ['App\Http\Controllers\Api\InvoiceController', 'getUpcomingInvoice']);
    Route::get('payment-history', ['App\Http\Controllers\Api\InvoiceController', 'getPaymentHistory']);
    Route::get('default-coupon', [CompanyController::class, 'getDefaultCoupon']);
   
    Route::get('deafult-payment-method', ['App\Http\Controllers\Api\InvoiceController', 'defaultPaymentMethod']);
      
    Route::post('upload-s3', [S3UploadController::class, 'uploadFile']);
    Route::post('/s3/upload-chunk', [S3UploadController::class, 'uploadChunk'])->name('upload-chunk');
    Route::post('/s3/multipart-upload', [S3UploadController::class, 'createMutiplartUpload']);
    Route::post('/s3/complete-upload', [S3UploadController::class, 'completeMutiplartUpload']);

});
