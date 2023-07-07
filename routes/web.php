<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Billing\CancelSubscriptionController;
use App\Http\Controllers\Billing\CardController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\ResumeSubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Billing\SubscriptionController;
use App\Http\Middleware\SuperAdminAccessPrivilege;
use App\Http\Middleware\BusinessCompanyAdminPrivilege;
use App\Http\Middleware\SuperAdminCompanyAdminPrivilege;
use App\Http\Middleware\CompanyAdminMemberAccessPrivilege;

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

Route::post('webhook/typeform', [AppController::class, 'handleWebhook'])->name('tf.webhook');

Route::post('set-locale', [AppController::class, 'setLocale']);

Route::group(['middleware' => ['setLocale']], function () {
    Route::get('/js/lang.js', [AppController::class, 'generateLangObject']);
});

Route::get('asset/{formId}', function ($formId) {
    return Inertia::render('PublicShare/Index', [
        'id' => $formId
    ]);
})->name('public.digital-asset');
Route::get('asset/{formId}/form', function (Request $request, $formId) {
    return Inertia::render('DigitalAsset/Public', [
        'id' => $formId, 'name' => $request->get('name'), 'email' => $request->get('email')
    ]);
})->name('public.digital-asset.form');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', function (Request $request) {
        return Inertia::render('Dashboard', ['user' => $request->user()->load('role')]);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::get('dashboard', function (Request $request) {
        return Inertia::render('Dashboard', ['user' => $request->user()->load('role')]);
    })->name('xme.overview');

    //Admin Routes
    Route::middleware([SuperAdminAccessPrivilege::class])->group(function () {

        //Company Routes
        Route::get('manage-companies', function () {
            return Inertia::render('Company/Index', ['is_archived' => 0, 'is_restore' => 0]);
        })->name('admin.manage-companies');

        /**Manage Company Soft Delete Routes */
        Route::get('manage-companies/archive', function () {
            return Inertia::render('Company/Index', ['is_restore' => 0, 'is_archived' => 1]);
        })->name('admin.manage-companies.archive');


        // Form Routes
        Route::get('manage-forms', function () {
            return Inertia::render('ManageForms/Index', ['is_archived' => 0, 'is_restore' => 0]);
        })->name('admin.manage-forms');

        /**Manage Form Soft Delete Routes */
        Route::get('manage-forms/archive', function () {
            return Inertia::render('ManageForms/Index', ['is_restore' => 0, 'is_archived' => 1]);
        })->name('admin.manage-forms.archive');

        //Email setting and templates Routes
        Route::get('email-settings', function () {
            return Inertia::render('EmailSettings/Index');
        })->name('email-settings.edit');

        Route::get('email-template', function () {
            return Inertia::render('EmailTemplate/Index');
        })->name('email_template.index');

        Route::get('email-template/edit/{id}', function ($id) {
            return Inertia::render('EmailEdit/Index', ['id' => decrypt_tech($id)]);
        })->name('email-setting.edit');

        //Review Assigned Company Module Routes
        Route::get('review-company-module', function () {
            return Inertia::render('ReviewCompanyModule/Index');
        })->name('app.review.company');

        //Form Builder Route
        Route::get('form-builder/', function () {
            return Inertia::render('Design/FormBuilder/Index');
        })->name('app.form-builder');

    });

    // Superadmin and company privilege
    Route::middleware([SuperAdminCompanyAdminPrivilege::class])->group(function () {

        //Members Routes
        Route::get('manage-members', function (Request $request) {
            return Inertia::render(
                'Member/Index',
                ['is_archived' => 0, 'is_restore' => 0, 'user' => $request->user()->load('role')]
            );
        })->name('admin.manage-users');

        Route::get('manage-members/archive', function (Request $request) {
            return Inertia::render('Member/Index', [
                'is_restore' => 0, 'is_archived' => 1,
                'user' => $request->user()->load('role')
            ]);
        })->name('admin.manage-users.archive');


        // Develop Routes
        Route::get('develop', function (Request $request) {
            return Inertia::render('Identify/Index', [
                'is_develop' => true, 'is_archived' => 0, 'is_restore' => 0,
                'user' => $request->user()->load('role')
            ]);
        })->name('app.develop');

        Route::get('develop/archive', function (Request $request) {
            return Inertia::render('Identify/Index', [
                'is_develop' => true, 'is_restore' => 0, 'is_archived' => 1,
                'user' => $request->user()->load('role')
            ]);
        })->name('app.develop.archive');

        Route::get('develop/edit/{id}', function (Request $request, $id) {
            return Inertia::render('Identify/Add', [
                'id' => decrypt_tech($id), 'is_develop' => 1,
                'user' => $request->user()->load('role')
            ]);
        })->name('app.develop-edit');

        // Review Form Routes
        Route::get('review-forms', function (Request $request) {
            return Inertia::render('ReviewForms/Index', ['user' => $request->user()->load('role')]);
        })->name('admin.review-forms');

        Route::get('review-history', function () {
            return Inertia::render('ReviewForms/IndexHistory');
        })->name('review-formshistory');


        // History Routes
        Route::get('review-module-history/{id}', function (Request $request, $id) {
            if ($request->get('company_id')) {
                $companyId = decrypt_tech($request->input('company_id'));
            }
            return Inertia::render('ReviewCompanyModule/History', [
                'id' => decrypt_tech($id),
                'user' => $request->user()->load('role'),
                'company_id' => $companyId
            ]);
        })->name('app.review.module.history');

    });

    // Company admin and member access privilige
    Route::middleware([CompanyAdminMemberAccessPrivilege::class])->group(function () {

        //my-training-page route
        Route::get('my-trainings/', function () {
            return Inertia::render('Trainings/Index');
        })->name('app.my-trainings');

        //  Library Routes
        Route::get('library', function (Request $request) {
            return Inertia::render('Library/Index', ['user' => $request->user()->load('role')]);
        })->name('app.library');

        Route::get('library/archive', function () {
            return Inertia::render('Library/Archive');
        })->name('library-archive');

        /**Identify Routes */
        Route::get('identify/', function (Request $request) {
            return Inertia::render(
                'Identify/Index',
                ['is_develop' => false, 'is_archived' => 0, 'is_restore' => 0, 'user' => $request->user()->load('role')]
            );
        })->name('app.identify');

        Route::get('identify/archive', function () {
            return Inertia::render('Identify/Index', ['is_develop' => false, 'is_restore' => 0, 'is_archived' => 1]);
        })->name('app.identify.archive');

        Route::get('identify/new', function (Request $request) {
            return Inertia::render('Identify/Add', ['is_develop' => 0, 'user' => $request->user()->load('role')]);
        })->name('identify-new');

        Route::get('identity/edit/{id}', function (Request $request, $id) {
            return Inertia::render('Identify/Add', [
                'id' => decrypt_tech($id), 'is_develop' => 0,
                'user' => $request->user()->load('role')
            ]);
        })->name('app.identity-edit');

    });

    Route::middleware([BusinessCompanyAdminPrivilege::class])->group(function () {

        /**Learning Path  Routes */
        Route::get('learning-path/', function () {
            return Inertia::render(
                'LearningPath/Index',
                ['is_archived' => 0, 'is_restore' => 0]
            );
        })->name('app.learning-path');

        Route::get('learning-path/archive', function () {
            return Inertia::render('LearningPath/Index', ['is_restore' => 0, 'is_archived' => 1]);
        })->name('app.learning-path.archive');

        Route::get('edit-learning/{id}', function ($id, Request $request) {
            return Inertia::render('LearningPath/Edit', ['id' => $id, 'user' => $request->user()->load('role')]);
        })->name('edit-learning');

        // Directory Routes

        Route::get('directory', function () {
            return Inertia::render('Directory/Index');
        })->name('app.directory');

        Route::get('directory/{id}', function ($id) {
            return Inertia::render('Directory/Index', ['id' => decrypt_tech($id)]);
        })->name('child-directory');

        // Report Route

        Route::get('Reports', function () {
            return Inertia::render('Reports/Index');
        })->name('app.reports');


        /**Manage Groups Soft Delete Routes */

        Route::get('manage-groups', function () {
            return Inertia::render(
                'ManageGroups/Index',
                ['is_archived' => 0, 'is_restore' => 0]
            );
        })->name('admin.manage-groups');

        Route::get('manage-groups/archive', function () {
            return Inertia::render('ManageGroups/Index', ['is_restore' => 0, 'is_archived' => 1]);
        })->name('admin.manage-groups.archive');

        Route::get('edit-group/{id}', function ($id) {
            return Inertia::render('ManageGroups/Edit', ['id' => decrypt_tech($id)]);
        })->name('edit-group');

        // In Production page Route

        Route::get('/new-assigned-modules', function () {
            return Inertia::render('ReviewForms/Assign');
        })->name('app.in-production-modules');

    });

    Route::get('complete-learning/{id}', function (Request $request, $id) {
        return Inertia::render('Trainings/Learning', [
            'id' => decrypt_tech($id),
            'user' => $request->user()->load('role')
        ]);
    })->name('complete-learning');



    //Xme Support Routes
    Route::get('xme-area', function (Request $request) {
        return Inertia::render('XmeArea/Index', ['is_archive' => 0, 'user' => $request->user()->load('role')]);
    })->name('app.xme-area');

    Route::get('xme-area/archive', function (Request $request) {
        return Inertia::render('XmeArea/Index', ['is_archive' => 1, 'user' => $request->user()->load('role')]);
    })->name('xme-area-archive');


    // Member Profile Route

    Route::get('profile', function () {
        return Inertia::render('Profile/Index');
    })->name('member-profile');

    // Business Profile Route

    Route::get('business-profile/{id}', function ($id) {
        return Inertia::render('BusinessProfile/Index', ['id' => $id]);
    })->name('business-profile');



    // TypeForm Response Routes

    Route::get('library/view/{formId}', function ($form_id, Request $request) {
        $response = '';
        if ($request->has('response')) {
            $response = $request->get('response');
        }
        return Inertia::render('Library/Responses', ['formId' => $form_id,'response' => $response]);
    })->name('library-view');

    Route::get('responses', function () {
        return Inertia::render('Library/ResponseIndex');
    })->name('response-view');



    //  Typeform Open Module Route
    Route::get('digital-asset/{id}', function (Request $request, $id) {
        $updation  = false;
        $heading_text =  'Module Manager';
        $learningpathId =  $tId = $updateActionType = $companyId= $formId = '';
        if ($request->get('updation')) {
            $updation  =  true;
            $updateActionType = 'reviewUpdateModule';
        }
        if ($request->get('type_module')) {
            $updation = true;
            $updateActionType = 'reviewCompanyModule';
        }
        if ($request->has('learning_path')) {
            $learningpathId = decrypt_tech($request->get('learning_path'));
        }
        if ($request->get('trainingid')) {
            $tId = $request->get('trainingid');
        }
        if ($request->get('company_id')) {
            $companyId = decrypt_tech($request->get('company_id'));
        }
        if ($request->get('form_id')) {
            $formId = decrypt_tech($request->get('form_id'));
        }
        if ($request->has('heading_text')) {
            $heading_text = ($request->get('heading_text'));
        }
        return Inertia::render('DigitalAsset/Index', [
            'id' => $id, 'updateActionType' => $updateActionType,
            'updation' => $updation, 'tId' => $tId, 'learningPathId' => $learningpathId,
            'redirectUrl' => $request->get('redirect_url'),
            'company_id'  => $companyId,
            'form_id'     => $formId,
            'heading_text' => $heading_text

        ]);
    })->name('digital-asset');

    // Notification Route
    Route::get('notification/list/', function (Request $request) {
        return Inertia::render('Notification/Index', ['user' => $request->user()->load('role')]);
    })->name('app.notification-list');

    //Activity  Log Route
    Route::get('activity/logs', function () {
        return Inertia::render('ActivityLog/Index');
    })->name('activity.logs');

    Route::get('developForm', function () {
        return Inertia::render('PpsForm/Index');
    })->name('developForm');

    // terms and conditions
    Route::get('/terms', function (Request $request) {
        if (
            $request->user()->load('role')->role->name ==  User::ROLE_EMPLOYEE ||
            $request->user()->load('role')->role->name ==  User::ROLE_BUSINESSADMIN
        ) {
            $page = 'Policy/UserTerms';
        } else {
            $page =  'Policy/TermPolicy';
        }
        return Inertia::render($page);
    })->name('app.terms-conditions');

    // privacy policy
    Route::get('/privacy-policy', function (Request $request) {
        if (
            $request->user()->load('role')->role->name ==  User::ROLE_EMPLOYEE ||
            $request->user()->load('role')->role->name ==  User::ROLE_BUSINESSADMIN
        ) {
            $page = 'Policy/UserPrivacy';
        } else {
            $page =  'Policy/PrivacyPolicy';
        }
        return Inertia::render($page);
    })->name('app.privacy-policy');

    Route::get('/user-terms', function () {
        return Inertia::render('Policy/UserTerms');
    })->name('app.user-terms');


    Route::get('/user-privacy-policy', function () {
        return Inertia::render('Policy/UserPrivacy');
    })->name('app.user-privacy-policy');


    Route::get('form-view/', function () {
        return Inertia::render('Design/FormBuilder/FormView');
    })->name('form-view');

    Route::get('/card', [CardController::class, 'PaymentMethod'])->name('card.get');
    // Payment Card
    Route::patch('/card', [CardController::class, 'update'])->name('card.update');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    Route::get('/subscription/cancel', [CancelSubscriptionController::class, 'cancelFlow'])
        ->name('subscription.cancel');
    Route::group(['middleware' => ['active.subscription']], function () {
        Route::put('/subscription/cancel', [CancelSubscriptionController::class, 'destroy'])
            ->name('subscription.destroy');
    });

    Route::get('subscriptions/details', [SubscriptionController::class, 'subscriptionDetails']);
    Route::get('/subscription/resume', [ResumeSubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::group(['middleware' => ['resume.subscription']], function () {
        // Resume Subscription
        Route::put('/subscriptions/resume', [ResumeSubscriptionController::class, 'update'])
            ->name('subscription.resume.update');
    });

    // My Subscription
    Route::get('subscriptions', function (Request $request) {
        $company = $request->user()->company;
        return Inertia::render('Stripe/Subscriptions', ['company' => $company]);
    })->name('subscriptions.index');
    Route::get('/subscription/plans', [SubscriptionController::class, 'get'])->name('pricing');
    Route::patch('/subscription/plans', [SubscriptionController::class, 'update'])->name('subscription.update');

    Route::post('/subscription/addons', [SubscriptionController::class, 'subscribeToAddon'])
        ->name('subscription.addons');
    Route::get('/subscription/addons', [SubscriptionController::class, 'addons'])
        ->name('subscription-plan.customize');

    Route::get('/subscription/verify', [SubscriptionController::class, 'verify'])
        ->name('subscription.verify');
    Route::delete('/subscription/incomplete', [SubscriptionController::class, 'incomplete'])
        ->name('incomplete.subscription');

    Route::get('plan_billing', function () {
        return Inertia::render('PlanBilling');
    })->name('plan_billing.index');


    /**subscription plans route */
    Route::get('create-plans', function () {
        return Inertia::render('SubscriptionPlan/Plan');
    })->name('admin.plans.create');


    Route::get('plan/edit/{id}', function (Request $request, $id) {
        return Inertia::render('SubscriptionPlan/Plan', [
            'id' => decrypt_tech($id),
        ]);
    })->name('app.plan-edit');
    Route::get('plans', function () {
        return Inertia::render('SubscriptionPlan/Index');
    })->name('admin.plans.index');

    Route::get('coming/', function () {
        return Inertia::render('ComingSoon');
    })->name('coming-soon');

    Route::get('error/', function () {
        return Inertia::render('Error');
    })->name('error');
});





Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::group(['prefix' => 'design'], function () {
    Route::get('/', function () {
        return Inertia::render('Sitemap');
    })->name('sitemap');
    //design url remove after development
    Route::get('business-profile/', function () {
        return Inertia::render('Design/BusinessProfile/Index');
    })->name('design-business-profile');

    Route::get('email-settings', function () {
        return Inertia::render('Design/EmailSettings/Index');
    })->name('design-email-settings');

    Route::get('email-template', function () {
        return Inertia::render('Design/EmailTemplate/Index');
    })->name('design-email-template');

    Route::get('email-template/edit/', function () {
        return Inertia::render('Design/EmailEdit/Index');
    })->name('design-email-edit');

    Route::get('manage-forms/', function () {
        return Inertia::render('Design/ManageForms/Index');
    })->name('design-manage-forms');

    Route::get('review-forms/', function () {
        return Inertia::render('Design/ReviewForms/Index');
    })->name('design-review-forms');

    Route::get('review-history/', function () {
        return Inertia::render('Design/ReviewForms/IndexHistory');
    })->name('design-review-formshistory');

    Route::get('review-company-module/', function () {
        return Inertia::render('Design/ReviewModule/Index');
    })->name('design-review-company-module');

    Route::get('updated-history', function () {
        return Inertia::render('Design/ReviewModule/IndexHistory');
    })->name('design-updated-history');

    Route::get('view-status/', function () {
        return Inertia::render('Design/ReviewModule/IndexStatus');
    })->name('design-view-status');

    Route::get('my-trainings/', function () {
        return Inertia::render('Design/Trainings/Index');
    })->name('design-my-trainings');

    Route::get('learning-path/', function () {
        return Inertia::render('Design/LearningPath/Index');
    })->name('design-learning-path');

    Route::get('edit-learning/', function () {
        return Inertia::render('Design/LearningPath/edit');
    })->name('design-edit-learning');

    Route::get('identify/', function () {
        return Inertia::render('Design/Identify/Index');
    })->name('design-identify');

    Route::get('identify/new/', function () {
        return Inertia::render('Design/Identify/IndexNew');
    })->name('design-identify-new');

    Route::get('directory/', function () {
        return Inertia::render('Design/Directory/Index');
    })->name('design-directory');

    Route::get('notification/list/', function () {
        return Inertia::render('Design/Notification/Index');
    })->name('design-notification-list');
});
require __DIR__ . '/auth.php';
