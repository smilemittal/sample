<?php

namespace App\Services;

use App\Http\Resources\ActivityLogCollection;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\MemberPendingInviteCollection;
use App\Http\Resources\TFResponseCollection;
use App\Http\Resources\TFResponseResource;
use App\Models\ActionLog;
use App\Models\AssignModuleUpdation;
use App\Models\Company;
use App\Models\Form;
use App\Models\CompanyForm;
use App\Models\Group;
use App\Models\Invite;
use App\Models\LearningPath;
use App\Models\Response;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashBoardCounterService
{
    public function companyCounters()
    {
        try {
            //new learning path
            $currentUser = Auth::user();
            $learningPaths = LearningPath::where('company_id', $currentUser->company_id);
            $learningPathConter = (clone $learningPaths)->count();
            $archiveLearningPathConter = (clone $learningPaths)->onlyTrashed()->count();

            //library
            $library = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->whereHas('form_company')->where('company_forms.module_status', 1)
            ->where('company_forms.company_id', $currentUser->company_id)
           // ->whereNull('company_forms.archived_at')
            ->where('xme_area', '=', 0);
            $activelibraryCollection = (clone $library)->where('forms.status', 1)->count();
            $inActiveLibraryCollection = (clone $library)->where('forms.status', 0)->count();

            $xmeArea = Form::scpCompany()->with('form_company')->where('xme_area', '=', 1)
            ->whereHas('form_company');
            $activeXmeAreaCollection =   (clone $xmeArea)->where('forms.status', 1)->count();
            $inActiveXmeAreaCollection = (clone $xmeArea)->where('forms.status', 0)->count();


            // review modules
            $forms = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
                        ->where('company_forms.company_id', Auth::user()->company_id)
                        ->where('company_forms.review_date', '<=', Carbon::now()->format('Y-m-d'));
            // ->where('company_forms.deleted_at', '=', null);

            $updateForms = Subject::where('form_id', '!=', 0)
                ->where('identify_reason', Subject::REVIEW_UPDATE)
                ->where('company_id', Auth::user()->company_id)->pluck('form_id');
            $forms = $forms->whereNotIn('forms.id', $updateForms);
            $reviewFormCounter = (clone $forms)->count();
            $reviewFormLibraryCounter = (clone $forms)->where('xme_area', 0)->count();
            $reviewFormXmeAreaCounter = (clone $forms)->where('xme_area', 1)->count();


            //new Assigned modules
            $assignedModules = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->where('company_forms.company_id', Auth::user()->company_id)
                // ->where('company_forms.deleted_at', null)
                ->where('company_forms.module_status', '=', 0)
                ->whereNull('company_forms.archived_at');
            $assignedModulesCouter = (clone $assignedModules)->count();
            $newAssignedModuleCounter = (clone $assignedModules)
            ->where(function ($query) {
                $query->where('company_forms.status', CompanyForm::NEW)
                      ->orWhereNull('company_forms.status');
            })->count();
            $updateAssignedModuleCounter = (clone $assignedModules)
            ->where('company_forms.status', CompanyForm::UPDATE)->count();
            $adminRequestAssignedModuleCounter = (clone $assignedModules)
            ->where('company_forms.status', CompanyForm::SEND_REQUSET_TO_ADMIN)->count();

            //groups
            $groups =  new GroupCollection(Group::scpCompany()->withCount('members', 'forms')->paginate(4));

            //developedModules
            $developedModule = Form::scpCompany()->where('xme_area', 0)->count();
            //Subject couters
            //develops
            $developQuery = Subject::scpCompany()->scpDevelop();
            $activeDevelopQuery = (clone $developQuery);
            $inActiveDevelopQuery = (clone $developQuery)->onlyTrashed();
            $developCount = (clone $activeDevelopQuery)->count();
            $inActiveDevelopCount = (clone $inActiveDevelopQuery)->count();
            $lowPriorityDevelopCount = (clone $activeDevelopQuery)
            ->where('priority', \App\Models\PRIORITY_subject::LOW)->count();
            $mediumPriorityDevelopCount = (clone $activeDevelopQuery)
            ->where('priority', \App\Models\PRIORITY_subject::MEDIUM)->count();
            $highPriorityDevelopCount = (clone $activeDevelopQuery)
            ->where('priority', \App\Models\PRIORITY_subject::HIGH)->count();
            $incompleted = (clone $activeDevelopQuery)
            ->where('status', \App\Models\STATUS_subject::DEVELOP)->count();
            $submitted = (clone $activeDevelopQuery)
            ->where('status', \App\Models\STATUS_subject::SUBMITTED)->count();
            $approved = (clone $activeDevelopQuery)
            ->where('status', \App\Models\STATUS_subject::READY)->count();
            // identifies
            $identifyQuery = Subject::scpCompany()->scpIdentify();
            $identifyCount = (clone $identifyQuery)->count();
            $lowPriorityIdentifyCount = (clone $identifyQuery)
            ->where('priority', \App\Models\PRIORITY_subject::LOW)->count();
            $mediumPriorityIdentifyCount = (clone $identifyQuery)
            ->where('priority', \App\Models\PRIORITY_subject::MEDIUM)->count();
            $highPriorityIdentifyCount = (clone $identifyQuery)
            ->where('priority', \App\Models\PRIORITY_subject::HIGH)->count();
            $draft = (clone $identifyQuery)
            ->where('status', '=', \App\Models\STATUS_subject::DRAFT)->count();

            //responses
            $companyResponses = Response::scpCompany()->with('xmeForm');
            $companyResponses->whereHas('xmeForm', function ($q) {
                $q->where('xme_area', 0);
            });
            $companyResponses = $companyResponses->whereIn('email', companyEmails());
            $companyResponses = $companyResponses->get()->sortByDesc('submitted_at')->all();
            $totalResponses = $responsesToday = $respLimiter = 0;
            $lastFourResponses = [];

            if ($companyResponses) {
                foreach ($companyResponses as $response) {
                    $totalResponses++;
                    if ($respLimiter < 4) {
                         $lastFourResponses[] = new TFResponseResource($response);
                        $respLimiter++;
                    }
                    if ($response->submitted_at->setTimezone(config('app.timezone'))->isToday()) {
                        $responsesToday++;
                    }
                }
            }

            // documentedcount
            return [
            'learningPathConter'        => $learningPathConter,
            'archiveLearningPathConter' => $archiveLearningPathConter,
            'reviewFormCounter'         => $reviewFormCounter,
            'reviewFormLibraryCounter'  => $reviewFormLibraryCounter,
            'reviewFormXmeAreaCounter'  => $reviewFormXmeAreaCounter,
            'assignedModulesCouter'     =>  $assignedModulesCouter,
            'newAssignedModuleCounter'  => $newAssignedModuleCounter,
            'updateAssignedModuleCounter' => $updateAssignedModuleCounter,
            'adminRequestAssignedModuleCounter' => $adminRequestAssignedModuleCounter,
            'identifyCount'               => $identifyCount,
            'lowPriorityIdentifyCount'  => $lowPriorityIdentifyCount,
            'mediumPriorityIdentifyCount' => $mediumPriorityIdentifyCount,
            'highPriorityIdentifyCount' => $highPriorityIdentifyCount,
            'draft'                     => $draft,
            'developCount'              => $developCount,
            'inActiveDevelopCount'      => $inActiveDevelopCount,
            'lowPriorityDevelopCount'   => $lowPriorityDevelopCount,
            'mediumPriorityDevelopCount'=> $mediumPriorityDevelopCount,
            'highPriorityDevelopCount'  => $highPriorityDevelopCount,
            'incompleted'               => $incompleted,
            'submitted'                 => $submitted,
            'approved'                  => $approved,
            'groups'                    => $groups,
            'groupCount'                => count($groups),
            'activelibraryCollection'   =>  $activelibraryCollection,
            'inActiveLibraryCollection' =>  $inActiveLibraryCollection,
            'activeXmeAreaCollection'   =>  $activeXmeAreaCollection,
            'inActiveXmeAreaCollection' =>  $inActiveXmeAreaCollection,
            'companyResponses'          =>  $lastFourResponses,
            'developedModule'           =>  $developedModule,
            'documented'                =>  $totalResponses,
            'responsesToday'           =>  $responsesToday
        ];
        } catch (\Exception | RequestException $e) {
            Log::error('company_couters_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function adminCounters()
    {
        try {
            // company counters
            $companyQuery = new Company();
            $company = $companyQuery->count();
            $archiveCompany = $companyQuery->onlyTrashed()->count();
            // form counters
            $formsQuery  = new Form();
            $forms = (clone $formsQuery)->where('xme_area', 0)->count();
            $xmeAreaForms = (clone $formsQuery)->where('xme_area', 1)->count();
            $activeForm = (clone $formsQuery)->where('xme_area', 0)->where('status', 1)->count();
            $nonActiveForm = (clone $formsQuery)->where('xme_area', 0)->where('status', 0)->count();
            $activeXmeArea = (clone $formsQuery)->where('xme_area', 1)->where('status', 1)->count();
            $inActiveXmeArea = (clone $formsQuery)->where('xme_area', 1)->where('status', 0)->count();


            // responses counter
            $companyResponses = new Response();
            $companyResponses = $companyResponses->get()->sortByDesc('submitted_at')->all();
            $totalResponses = $responsesToday = $respLimiter = 0;
            $lastFourResponses = [];
            if ($companyResponses) {
                foreach ($companyResponses as $response) {
                    $totalResponses++;
                    if ($respLimiter < 4) {
                         $lastFourResponses[] = new TFResponseResource($response);
                        $respLimiter++;
                    }
                    if ($response->submitted_at->setTimezone(config('app.timezone'))->isToday()) {
                        $responsesToday++;
                    }
                }
            }
            
            // users counters
            $userQuery = new User();
            $users = $userQuery->count();
            $archiveUsers = $userQuery->onlyTrashed()->count();

            //review company modules
            $pendingModule = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
             ->join('companies', 'companies.id', 'company_forms.company_id')
            ->where('company_forms.status', CompanyForm::SEND_REQUSET_TO_ADMIN)->count();


            $historyModule = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id')
            ->join('companies', 'company_forms.company_id', '=', 'companies.id')
            ->join('assign_module_updations', 'assign_module_updations.form_id', 'company_forms.form_id')
            ->where('assign_module_updations.status', '!=', AssignModuleUpdation::PENDING)
            ->groupBy('assign_module_updations.form_id')
            ->groupBy('assign_module_updations.company_id')->get();
            // developedCounter
            $developQuery = new Subject();
            $developed = Subject::scpDevelop()->count();
            $adminDevelops = (clone $developQuery)->where(function ($query) {
                $query->where('status', '=', \App\Models\STATUS_subject::SUBMITTED)
                ->orWhere('status', '=', \App\Models\STATUS_subject::REOPEN);
            });
            $adminArchiveDevelopCount = (clone $adminDevelops)->onlyTrashed()->count();

            $adminDevelopCount = (clone $adminDevelops)->count();
            $highPriorityDevelopCount = (clone $adminDevelops)->where('priority', \App\Models\PRIORITY_subject::HIGH)
            ->count();
            $lowPriorityDevelopCount = (clone $adminDevelops)->where('priority', \App\Models\PRIORITY_subject::LOW)
            ->count();
            $mediumPriortyDevelopCount = (clone $adminDevelops)->where('priority', \App\Models\PRIORITY_subject::MEDIUM)
            ->count();
            $reOpenDevelopCount = (clone $developQuery)->where('status', \App\Models\STATUS_subject::REOPEN)->count();
            $submittedDevelopCount = (clone $developQuery)->where('status', \App\Models\STATUS_subject::SUBMITTED)
            ->count();
            // pending invites
            $pendingInvites = new MemberPendingInviteCollection(Invite::scpCompany()->latest()->take(4)->get()->all());
            $groups =      new GroupCollection(Group::scpCompany()->latest()->take(4)->get()->all());
            $activityLogs =     new ActivityLogCollection(ActionLog::scpCompany()->latest()->take(4)->get());
            
            //identified counter
            $identifies =  (Subject::count());

            //deelopedModules
            $developedModule = Form::scpCompany()->where('xme_area', 0)->count();
            return [
                'totalDelivered' => $totalResponses,
                'responsesToday' => $responsesToday,
                'companies'      => $company,
                'archiveCompany' => $archiveCompany,
                'forms'          => $forms,
                'xmeAreaForms'   => $xmeAreaForms,
                'activeForms'    => $activeForm,
                'inActiveForms'  => $nonActiveForm,
                'activeXmeArea'  => $activeXmeArea,
                'inActiveXmeArea' => $inActiveXmeArea,
                'devloped'       => $developed,
                'identifies'     => $identifies,
                'users'        => $users,
                'archiveUsers'   => $archiveUsers,
                'adminDevelopCount' => $adminDevelopCount,
                'adminArchiveDevelopCount' => $adminArchiveDevelopCount,
                'highPriorityDevelopCount' => $highPriorityDevelopCount,
                'lowPriorityDevelopCount'  => $lowPriorityDevelopCount,
                'mediumPriortyDevelopCount' => $mediumPriortyDevelopCount,
                'reOpenDevelopCount'        => $reOpenDevelopCount,
                'submittedDevelopCount'     => $submittedDevelopCount,
                'responses'                 => new TFResponseCollection($lastFourResponses),
                'pendingMembers'            => $pendingInvites,
                'groups'                    => $groups,
                'activityLogs'              => $activityLogs,
                'pendingModule'             => $pendingModule,
                'historyModule'             => count($historyModule),
                'documented'                => $totalResponses,
                'developedModule'           =>  $developedModule,
            ];
        } catch (\Exception | RequestException $e) {
            Log::error('admin_couters_service', ['error' => $e->getMessage()]);
            throw $e;
        }

    }

    public function memberCounters()
    {
        try {
            // check identify and develop
            $subjects = Subject::scpCompany()->scpIdentify();
            $subjectsCount = (clone $subjects)->count();
            $lowPriorityCount = (clone $subjects)->where('priority', \App\Models\PRIORITY_subject::LOW)->count();
            $mediumPriorityCount = (clone $subjects)
            ->where('priority', \App\Models\PRIORITY_subject::MEDIUM)->count();
            $highPriorityCount = (clone $subjects)
            ->where('priority', \App\Models\PRIORITY_subject::HIGH)->count();
            $draft = (clone $subjects)
            ->where('status', \App\Models\STATUS_subject::DRAFT)->count();
            return [
                'totalCounts' => $subjectsCount,
                'lowPriorityCount' => $lowPriorityCount,
                'mediumPriorityCount' => $mediumPriorityCount,
                'highPriorityCount' => $highPriorityCount,
                'draft'               => $draft
                ];
        } catch (\Exception | RequestException $e) {
            Log::error('member_counter_service', ['error' => $e->getMessage()]);
            throw $e;
        }

    }


}
