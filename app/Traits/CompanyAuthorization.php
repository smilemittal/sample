<?php

namespace App\Traits;

use Auth;

trait CompanyAuthorization
{
    /**
     * Get the user's Authorization information
     *
     * @return string
     */
    public function getComapnyAuth($comapny = null)
    {
        $user = false;
        $role = false;
        $companyAllow = [];
        $companyCan = [];
        $companyUsed = [];
        $userCompany = false;
        if (auth()->check()) {
            $role = auth()->user()->role;
            $user = auth()->user();
        }
        if (($user && !isSuperAdmin()) || $comapny) {
            $userCompany = $comapny ?? $user->company;
            $activePlan = $userCompany->activePlan() ?? freePlan();
            if ($activePlan) {
                $companyAllow = $activePlan->description;
            }
            $companyUsed = [
                'members' => $userCompany->members->count(),
                'company_admin' => $userCompany->company_admin->count(),
                'company_sub_admin' => $userCompany->company_sub_admin->count(),
                'total_users'  => 0,
            ];
            $companyUsed['total_users'] = $companyUsed['members'] + $companyUsed['company_admin'] +
            $companyUsed['company_sub_admin'] ;

        }
        foreach ($companyAllow as $key => $value) {
            if (array_key_exists($key, $companyUsed) && array_key_exists($key, $companyAllow)) {
                $companyCan[$key] = ! ($companyAllow[$key] - $companyUsed[$key] <= 0);
            } else {
                if (is_bool($companyAllow[$key])) {
                    $companyCan[$key] = $companyAllow[$key];
                }
            }
        }
        return compact('companyAllow', 'companyUsed', 'companyCan', 'role', 'userCompany', 'user');
    }
}
