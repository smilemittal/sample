<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Jobs\XmeActionLog;

use Illuminate\Support\Facades\DB;
use App\Models\EmailTemplate;

class EmailTemplatesService
{

    public function index($inputs)
    {
        try {
            $data = [];
            $emailTemplates =  EmailTemplate::orderBy($inputs['sortField'], $inputs['orderDir']);
            $text = '';
            if (isset($inputs['search'])) {
                $search = trim($inputs['search']);
                $emailTemplates = $emailTemplates->where('subject', 'LIKE', "%{$search}%");
                // /**search action logs */
                $text = 'searched subjects by "' . $inputs['search'] . '"';
                // dispatch(
                //     new XmeActionLog(
                //         auth()->user(),
                //         'search',
                //         '{user} searched subjects by "' . $inputs['search'] . '" on email template page',
                //         null,
                //     )
                // );
            }
            $emailTemplates = $emailTemplates->paginate($inputs['perpage']);
            if (!empty($inputs['perpage'])) {
                $data[] = 'Entries  "' .$inputs['perpage'] . '"';
            }
            /**activity logs */
            $pageName = ' on email template page';
             if (!empty($text) && !empty($data)) {
                $this->updateAllFilterLog($text, $data, $pageName);
            }
            return $emailTemplates;
        } catch (\Exception | RequestException $e) {
            Log::error('email_templates_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function show($id)
    {
        try {
            $emailTemplates = EmailTemplate::find($id);
            /** edit email template action logs */
            dispatch(new XmeActionLog(
                auth()->user(),
                'edit',
                '{user} is editing email "{model}"',
                $emailTemplates,
            ));
            return $emailTemplates;
        } catch (\Exception | RequestException $e) {
            Log::error('email_template_edit_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($inputs, $id)
    {
        try {
            DB::beginTransaction();
            $emailTemplates = EmailTemplate::find($id);
            $emailTemplates->update($inputs);
            dispatch(new XmeActionLog(
                auth()->user(),
                'update',
                '{user} updated email  "{model}" in email template.',
                $emailTemplates,
            ));
            DB::commit();
            return $emailTemplates;
        } catch (\Exception | RequestException $e) {
            DB::rollback();
            Log::error('email_template_update_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function destroy($id)
    {
        try {
            return EmailTemplate::find($id)->delete();
        } catch (\Exception | RequestException $e) {
            Log::error('email_template_destroy_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateAllFilterLog($text,  $data, $pageName)
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
