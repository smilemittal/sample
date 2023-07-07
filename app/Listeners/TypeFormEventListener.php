<?php

namespace App\Listeners;

use App\Events\TypeFormWebhookReceived;
use App\Models\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class TypeFormEventListener
{
    /**
     * Handle received Type Form webhooks.
     *
     * @param  \App\Events\TypeFormWebhookReceived  $event
     * @return void
     */
    public function handle(TypeFormWebhookReceived $event)
    {
        try {
            $data = $event->payload['form_response'];
            $response = [];

            Log::debug('Type Form event response:', [$event->payload]);
            if (Response::where('response_id', $data['token'])->exists()) {
                Log::debug('duplicate response found');
                $response[] = "duplicate entry";
                return response()->json($response, 200);
            }
            $timeRequested = new \DateTime("now", new \DateTimeZone("UTC"));
            $typeformId = $data['form_id'];
            // extract the email
            $resEmail = "no email found";
            $resName = "no Name";
            foreach ($data['answers'] as $answer) {
                if ($answer['type'] == 'email') {
                    $resEmail = $answer['email'];
                    $retstr[] = $resEmail;
                }
            }

            if ($resEmail == "no email found" && !empty($data['hidden'])) {
                foreach ($data['hidden'] as $key => $hidden) {
                    if ($key == 'email') {
                        $resEmail = $hidden;
                        $retstr[] = $resEmail;
                    } elseif ($key == 'name') {
                        $resName = $hidden;
                    }
                }
            }

            $submittedDate = Carbon::parse($data['submitted_at']);
            $submittedDate->setTimezone(config('app.timezone'));

            if (Response::create([
                'email' => $resEmail,
                'name' => $resName,
                'json' => json_encode($data, true),
                'response_id' => $data['token'],
                'typeform_id' => $typeformId,
                'time_requested' => $timeRequested,
                'submitted_at' => $submittedDate->format('Y-m-d H:i:s'),
                // 'submitted_at' => new \DateTime($singleResponse['submitted_at'], new \DateTimeZone("UTC"))
            ])) {
                Log::debug('response saved');
                $retstr[] = "response saved";
            } else {
                Log::debug('could not save response');
                $retstr[] = "response could NOT be saved";
            }
            return response()->json($response, 200);
        } catch (Exception $ex) {
            report($ex);
            return response()->json($ex->getMessage(), 500);
        }
    }

    
}
