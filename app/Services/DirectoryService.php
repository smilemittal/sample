<?php

namespace App\Services;

use App\Jobs\XmeActionLog;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Directory;
use App\Models\Form;
use App\Models\DirectoryModule;
use App\Notifications\AddModuleToDirectoryNotification;
use App\Notifications\DeleteDirectoryModuleNotification;
use App\Notifications\UpdateDirectoryNotification;
use App\Helpers\NotificationHelper;
use App\Interfaces\CommonConstants;
use App\Jobs\SendNotification;
use Illuminate\Validation\Rules\Exists;

class DirectoryService
{
    public function list($inputs)
    {
        try {
            $data = [];
            $perPage = !empty($inputs['per_page']) ? $inputs['per_page'] : CommonConstants::DEFAULT_PAGINATION;
            $parentId = (!empty($inputs['parent_id']) ? $inputs['parent_id'] : 0);
            $with = ['parent'];
            if ($perPage == 'all') {
                $with = ['children'];
            }
            $directory = Directory::with($with)->where('parent_id', $parentId)
                ->where('company_id', Auth::user()->company_id);
            $text = '';
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $directory = $directory->where(function ($query) use ($search) {
                    $query->where('directories.name', 'LIKE', "%{$search}%");
                });
                // $pageName = 'on directories page.';
                // $searchName = 'searched directories by';
                // $this->updateSearch($searchName, $search, $pageName);
                $text = 'searched directories by "' . $inputs['search'] . '"';
            }
            $directory = $directory->orderBy($inputs['sortField'], $inputs['orderDir']);
            /**activity logs */
            $pageName = 'on directories page.';
            if (!empty($data) || !empty($text)) {
               $this->updateAllFilterLog($text, $data, $pageName);
           }
            return $perPage != 'all' ? $directory->paginate($perPage) : $directory->get();
        } catch (\Exception | RequestException $e) {
            Log::error('directory_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function store($inputs)
    {
        try {
            DB::beginTransaction();
            $xmeDirectory = Directory::create([
                'name' => $inputs['name'], 'parent_id' =>
                $inputs['parent_id'], 'company_id' => Auth::user()->company_id
            ]);

            $company    = Company::where('id', $xmeDirectory->company_id)->first();
            $this->fireDirectoryCreationMail($xmeDirectory, $company);
            /***activity logs create sub directory **/
            $existDirectory = Directory::find($inputs['parent_id']);
            if ($existDirectory) {
                $action = 'created the  sub directory';
                $this->directoryLogs($action, $inputs['name'], $existDirectory->name, $xmeDirectory);
            }

            /***activity log create main directory*/
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user} created  directory "' . $inputs['name'] . '" ',
                $xmeDirectory,
            ));

            DB::commit();
            return $xmeDirectory;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('directory_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($inputs, $id)
    {
        try {
            DB::beginTransaction();
            $directory = Directory::find($id);
            $directory->name = $inputs['name'];
            $directory->save();
            $xmeDirectory = $directory;
            $company    = Company::where('id', $xmeDirectory->company_id)->first();
            $this->fireDirectoryUpdationMail($xmeDirectory, $company);
            /** updated sub directory activity logs */
            $existDirectory = $directory->where('parent_id', $inputs['parent_id'])->first();
            if ($existDirectory) {
                $action = 'updated the  sub directory';
                $this->directoryLogs($action, $inputs['name'], $existDirectory->name, $xmeDirectory);
            }

            /** updated main directory activity logs */
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated directory "' . $inputs['name'] . '"',
                $xmeDirectory,
            ));
            DB::commit();
            return $xmeDirectory;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('directory_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function edit($directoryId)
    {
        try {
            $xmeDirectory = Directory::find($directoryId);
            return $xmeDirectory;
        } catch (\Exception | RequestException $e) {
            Log::error('directory_edit_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function pendingDirectoryModule($inputs, $directoryId)
    {
        try {
            $data = [];
            $modules = new Form();
            $modules = $modules->whereDoesntHave('directoryModules', function ($q) use ($directoryId) {
                $q->where('directory_id', $directoryId);
            });
            $modules = $modules->whereHas('form_company', function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('module_status', 1);
            });
            $text = '';
            if (isset($inputs['search'])) {
                $search =  trim($inputs['search']);
                $modules = $modules->where(function ($query) use ($search) {
                    $query->where('typeform_id', 'LIKE', "%{$search}%")
                        ->orWhere('display_title', 'LIKE', "%{$search}%");
                });
                $text = 'searched pending modules by "' . $search . '"';
                // $pageName = 'on directory module page.';
                // $searchName = 'searched modules by';
                // $this->updateSearch($searchName, $search, $pageName);
            }
            if (isset($inputs['type']) && $inputs['type'] == 'xme-area') {
                $modules->where('xme_area', 1);
                $data[] = 'type " Xme area "';
                // $pageName = 'on pending modules directories pages';
                // $this->updatedFilterType($inputs['type'], $pageName);
            } elseif (isset($inputs['type']) && $inputs['type'] == 'library') {
                $modules->where('xme_area', 0);
                $data[] = 'type " Library "';
                // $pageName = 'on pending modules directories pages';
                // $this->updatedFilterType($inputs['type'], $pageName);
            }
            $modules =   $modules->get();
             /**activity logs */
             $pageName = 'on pending modules of directories pages.';

             if (!empty($text)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $modules;
        } catch (\Exception | RequestException $e) {
            Log::error('pending_directory_module_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function addModulesToDirectory($inputs, $directoryId)
    {
        try {
            DB::beginTransaction();
            $xmeDirectory = Directory::find($directoryId);
            $xmeDirectoryModule = DirectoryModule::where('form_id', $inputs['form_id'])
                ->where('company_id', Auth::user()->company_id)->where('directory_id', $directoryId)->first();
            if (!$xmeDirectoryModule) {
                $xmeDirectoryModule = new DirectoryModule();
                $xmeDirectoryModule->directory_id = $directoryId;
                $xmeDirectoryModule->form_id = $inputs['form_id'];
                $xmeDirectoryModule->company_id = Auth::user()->company_id;
                $xmeDirectoryModule->save();
                $company = Auth::user()->company;
                $this->fireDirectoryAddModuleMail($xmeDirectoryModule, $company);
                $module = $xmeDirectoryModule->directoryModules->display_title;
                dispatch(new XmeActionLog(
                    auth()->user(),
                    'store',
                    '{user}  add module "' . $module . '" in  "' . $xmeDirectory->name . 'Directory"
                     on directories page',
                    $xmeDirectory,
                ));
            }
            DB::commit();
            return $xmeDirectoryModule;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('add_module_in_dircetoty_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteDirectory($id)
    {
        try {
            DB::beginTransaction();
            $directories = Directory::find($id);
            dispatch(new XmeActionLog(
                auth()->user(),
                'store',
                '{user}  remove "' . $directories->name . ' Directory"
                     on directories page',
                $directories,
            ));

            $deletionDir = Directory::getChildren($directories->id);
            array_unshift($deletionDir, $directories->id);

            DirectoryModule::whereIn('directory_id', $deletionDir)->delete();

            Directory::whereIn('id', $deletionDir)->delete();
            // $this->mailDeleteDirectory($directories);
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('delete_directory_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function directoryModules($inputs, $directoryId)
    {
        try {
            $forms = Form::join('company_forms', 'company_forms.form_id', '=', 'forms.id');
            $forms = $forms->where('company_forms.module_status', 1);
            $forms = $forms->join(
                'directory_modules',
                'directory_modules.form_id',
                '=',
                'forms.id'
            )
                ->where('directory_modules.directory_id', $directoryId);
            $forms = $forms->orderBy('company_forms.display_order', 'ASC')
                ->where('company_forms.company_id', Auth::user()->company_id)
                ->whereNull('company_forms.archived_at');
            if (isset($inputs['isArchived'])) {
                $forms = $forms
                    ->where(function ($query) {
                        $query->whereNotNull('company_forms.archived_at')->orwhereNotNull('forms.archived_at');
                    });
            } else {
                $forms =  $forms->whereNull('company_forms.archived_at')->whereNull('forms.archived_at');
            }
            $forms = $forms->select('forms.*', 'company_forms.review_date as form_review_date')->paginate(5);
            return $forms;
        } catch (\Exception | RequestException $e) {
            Log::error('pending_directory_module_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteDirectoryModule($inputs, $formId)
    {
        try {
            DB::beginTransaction();
            $xmeDirectoryModule =  DirectoryModule::with('directoryModules', 'directory')
                ->where('form_id', $formId)->where('directory_id', $inputs['directory_id'])
                ->where('company_id', Auth::user()->company_id)->first();
            $this->mailModuleDeleteFromDirectory($xmeDirectoryModule);
          

            dispatch(new XmeActionLog(
                auth()->user(),
                'delete',
                '{user}  remove  ' . $xmeDirectoryModule->directoryModules->display_title .
                    ' module  "'
                    . $xmeDirectoryModule->directory . '" Directory',
                $xmeDirectoryModule,
            ));
            $xmeDirectoryModule->delete();

            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
            DB::rollBack();
            Log::error('directory_module_delete_api', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /** directory create email bell notifications **/
    public function fireDirectoryCreationMail($xmeDirectory, $company)
    {
        $loggedUser = Auth::user();
        $users = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'directory' => $xmeDirectory,
            'company' => $company,
            'isSupport' => $isSupport,
            'loggedUser' => $loggedUser
        ];

        dispatch(new SendNotification(
            $users,
            $data,
            'AddDirectoryNotification',
            'sendCreatedDirectoryNotification',
            null
        ));
    }

    /**fireEmailUpdateDirectory**/
    public function fireDirectoryUpdationMail($xmeDirectory, $company)
    {

        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'company'       => $company,
            'directory'     => $xmeDirectory,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'UpdateDirectoryNotification',
            'sendUpdatedDirectoryNotification',
            null
        ));
    }

    /** directory add module email bell notifications **/
    public function fireDirectoryAddModuleMail($xmeDirectory, $company)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }

        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'          => $loggedUser,
            'isSupport'           => $isSupport,
            'company'             => $company,
            'directoryModule'     => $xmeDirectory,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'AddModuleToDirectoryNotification',
            'sendAddModuleToDirectoryNotification',
            null
        ));
    }

    /** directory delete module email bell notifications **/
    public function mailModuleDeleteFromDirectory($xmeDirectory)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $company = Company::find($xmeDirectory->company_id);
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();
        $data = [
            'loggedUser'          => $loggedUser,
            'isSupport'           => $isSupport,
            'company'             => $company,
            'directoryModule'     => $xmeDirectory,
        ];
        dispatch(new SendNotification(
            $users,
            $data,
            'DeleteDirectoryModuleNotification',
            'sendDeleteDirectoryModuleNotification',
            null
        ));
    }

    /** delete directory email bell notifications function **/
    public function mailDeleteDirectory($xmeDirectory)
    {
        $loggedUser = Auth::user();
        $users      = [];
        if (isImpersonatedSuperAdmin()) {
            $isSupport = true;
        } else {
            $isSupport = false;
        }
        $users = User::with('role')->where('company_id', $loggedUser->company_id)
            ->whereHas('role', function ($q) {
                $q->where('name', '!=', User::ROLE_EMPLOYEE);
            })->where('id', '!=', Auth::user()->id)->get();

        $data = [
            'loggedUser'    => $loggedUser,
            'isSupport'     => $isSupport,
            'directory'     => $xmeDirectory,
        ];

        dispatch(new SendNotification(
            $users,
            $data,
            'DeleteDirectoryNotification',
            'sendDeleteDirectoryNotification',
            null
        ));
    }
    /** activity logs search */
    public function updateSearch($searchName, $search, $pageName)
    {
        if ($search != '') {
            dispatch(
                new XmeActionLog(
                    auth()->user(),
                    'search',
                    '{user} ' . $searchName . ' "' . $search . '" ' . $pageName . '',
                    null,
                )
            );
        }
    }

    /*** action logs sub directory */
    public function directoryLogs($action, $subDirectory, $mainDirectory, $xmeDirectory)
    {
        dispatch(new XmeActionLog(
            auth()->user(),
            'store',
            '{user} ' . $action . ' "' . $subDirectory . '"
                         from main directory "' . $mainDirectory . '"',
            $xmeDirectory,
        ));
    }



    /*** activity logs change filter for type  pending modules directory page */
    public function updatedFilterType($type, $pageName)
    {
        if ($type != '') {
            dispatch(new XmeActionLog(
                auth()->user(),
                'filter',
                '{user} change filtered  type by "' . $type . '" ' . $pageName . '',
                null,
            ));
        }
    }

    public function updateAllFilterLog($text, $data, $pageName)
{
        if (!empty($data)) {
            $text .= " filters by " . implode(', ', $data);
        }
        dispatch(
            new XmeActionLog(
                auth()->user(),
                'search',
                '{user} ' . $text . ' ' . $pageName . '',
                null,
            )
        );
}
}
