<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class NotificationService
{
    /**notification index function */
    public function index($user)
    {
        try {
            return $user->notifications()->paginate(10);
        } catch (\Exception | RequestException $e) {
                Log::error('notification_list_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /** delete notification function */
    public function deleteNotification($notificationId)
    {
        try {
            DB::beginTransaction();
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_delete_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /**unmarked notification function */
    public function unMarked($notificationId)
    {
        try {
            DB::beginTransaction();
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->read_at=null;
                $notification->save();
            }
            DB::commit();
            return $notification;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_unmarked_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /**marked notification function  */
    public function marked($notificationId)
    {
        try {
            DB::beginTransaction();
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->read_at=Carbon::now();
                $notification->save();
            }
            DB::commit();
            return $notification;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_marked_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /**clear notification function */
    public function clearNotification()
    {
        try {
            DB::beginTransaction();
            $notification = Auth::user()->notifications();
            if ($notification) {
                $notification->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_clear_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /**marked all notification function */
    public function markedAll()
    {
        try {
            DB::beginTransaction();
            $notifications=Auth::user()->notifications()->get();
            foreach ($notifications as $notification) {
                $notification->read_at=Carbon::now();
                $notification->save();
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_marked_all_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    /** unmarked all notification function */
    public function unMarkedAll()
    {
        try {
            DB::beginTransaction();
            $notifications=Auth::user()->notifications()->get();
            foreach ($notifications as $notification) {
                $notification->read_at=null;
                $notification->save();
            }
            DB::commit();
            return true;
        } catch (\Exception | RequestException $e) {
                Log::error('notification_unmarked_all_service', ['error' => $e->getMessage()]);
                throw $e;
        }
    }

    public function unreadNotifications(User $user)
    {
        try {
            return $user->unreadNotifications()->orderBy('created_at', 'DESC')->get();
        } catch (\Exception | RequestException $e) {
            Log::error('unread_notification_list_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}