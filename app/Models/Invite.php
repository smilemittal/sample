<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Invite extends Model
{
    use HasFactory, Notifiable;

    protected $fillable=[
        'email',
        'token',
        'company_id',
        'invite_by',
        'invited_user_id',
      ];


      public function scopeScpCompany($query)
      {
          return User::hasRole(Auth::user(), 'superadmin') ?
           $query : $query->where('company_id', Auth::user()->company_id);
          
      }

      public function company()
      {
          return $this->belongsTo(Company::class, 'company_id', 'id');
      }

      public function inviteBy(){
          return $this->belongsTo(User::class, 'invite_by', 'id');
      }

      public function invitedUser(){
          return $this->belongsTo(User::class, 'invited_user_id', 'id');
      }

      public function list($filterArr = false, $fields = false, $orderBy = false, $order = false,
      $perPage = false, $with = false)
          {
              if ($fields == false) {
              $fields = ['invites.*',];
              }
              $query = Invite::select($fields)->scpCompany()->whereNull('invited_user_id');
              $search = isset($filterArr['search']) ? $filterArr['search'] : '';
              if ($search) {
                        $query = $query->where(function ($inviteQuery) use ($search) {
                            $inviteQuery->where('invites.email', 'LIKE', "%{$search}%")
                            ->orWhereHas('company', function ($q) use ($search) {
                                $q->where('name', 'LIKE', "%{$search}%");
                            });
                        });
               }
              $query = $query->join('companies', 'companies.id', '=', 'invites.company_id');
              if ($orderBy == false && $order == false) {
              $orderBy = 'invites.id';
              $order = 'DESC';
              }
              $query->orderBy($orderBy, $order);
              return $perPage ? $query->paginate($perPage) : $query->get();
          }
}
