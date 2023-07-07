<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\LoginService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    //
    use ApiResponse;
    public function login(Request $request)
    {
        $responseArr = [];
        $validations  = [
            'email' => 'required | email',
            'password' => 'required',
        ];
        $inputs = $request->all();
        $request->validate($validations);
        $redirectUrl = $token =  '';
        if (!empty($request->input('redirect_url'))) {
            $redirectUrl = $request->input('redirect_url');
        }
        if (Auth::attempt(array('email' => $request->input('email'), 'password' =>
         $request->input('password')), true)) {
            if (User::hasRole(Auth::user(), User::ROLE_SUPERADMIN) || Auth::user()->company) {
                $this->tryDeleteInvite($inputs['email']);
                if (!empty($inputs['redirect_url'])) {
                    $redirectUrl = $inputs['redirect_url'];
                } elseif (User::hasRole(Auth::user(), User::ROLE_EMPLOYEE)) {
                    $redirectUrl = 'app.my-trainings';
                } else {
                    $redirectUrl = 'xme.overview';
                }
                $token = Auth::user()->createToken("API TOKEN")->plainTextToken;
            } else {
                $errorMessage = trans('messages.company_blocked_msg');
                Auth::logout();
                $responseArr['errors'] = ['email_psw' => [$errorMessage]];
                return $this->failResponse($responseArr, 422);
            }
        } else {
            $errorMessage = trans('messages.email_and_password_are_wrong');
            $responseArr['errors'] = ['email_psw' => [$errorMessage]];
            return $this->failResponse($responseArr, 422);
        }
        try {
            $responseArr['data'] = [ $token , $redirectUrl];
            $responseArr['message']  = trans('messages.login_in_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('login_create_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }
    }

    public function tryDeleteInvite($email)
    {
        $invite = Invite::where('email', $email)->first();
        if ($invite) {
            $invite->delete();
        }
    }

    public function companyRegister(Request $request)
    {
        $inputs = $request->all();
        $responseArr = [];
        $validations  = [
            'email' => 'required | email|unique:users|',
            'password' => 'required',
            'company_name'     =>['required','unique:companies,name'],
            'abn'      => ['required','unique:companies,abn'],
            'first_name'  => 'required',
            'last_name' => 'required'
        ];
        $request->validate($validations);

        $company = Company::create(['name' => $inputs['company_name'],'abn' => $inputs['abn']]) ;
        $user = User::create(['email' => $inputs['email'],
         'password' =>  Hash::make($inputs['password']),
         'company_id'  => $company->id,
         'role_id' => 4,
         'firstname' => $inputs['first_name'],
         'lastname'  => $inputs['last_name'],
         'name'      => $inputs['first_name'] .' '. $inputs['last_name']
        ]);
        Auth::loginUsingId($user->id);
        try {
            $responseArr['data'] = [$user,$company];
            $responseArr['message']  = trans('messages.company_register_successfully');
            return $this->successResponse($responseArr);
        } catch (\Exception $e) {
            Log::error('login_create_api', ['error' => $e]);
            report($e);
            return $this->failResponse();
        }

    }
}
