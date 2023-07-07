<?php

namespace App\Console\Commands;

use App\Models\Form;
use App\Models\Response;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResponseCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:response-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*
               TODO:
                       [ ] Grab responses only from a set date (last request date)
                       [ ] Check for duplicate before saving
                       [ ] Time restriction for nr of requests

               Make db field time_requested
               Get the timestamp before calling api
               Insert the timestamp with each response

               Call the api from last time_requested
           */
        // 1. check for json

        Log::debug("ResponseCron in:");
        $localForms = Form::get();

        foreach ($localForms as $singleForm) {
            // get the timestamp for calling the api
            $time_requested = new \DateTime("now", new \DateTimeZone("UTC"));

            $response = Http::withToken('HK5cFuhtjbN9wMV19YFqG7K76MF98qqTPA8QEzNZSSJd')
            ->get("https://woofeculture.typeform.com/forms/$singleForm->typeform_id/responses");

            $resArray = $response->json();
            $count = $resArray['total_items'];
            $seperatedItems = $resArray['items'];
            Log::debug('total items'.$count);

            foreach ($seperatedItems as $singleResponse) {
                // extract the email
                $resEmail = "no email found";
                $resName = "no Name";
                foreach ($singleResponse['answers'] as $answer) {
                    if ($answer['type'] == 'email') {
                        $resEmail = $answer['email'];
                        Log::debug('this email: '.$resEmail);
                    }
                }

                if ($resEmail == "no email found" && !empty($singleResponse['hidden'])) {
                    foreach ($singleResponse['hidden'] as $key => $hidden) {
                        if ($key == 'email') {
                            $resEmail = $hidden;
                            Log::debug('this email: ' . $resEmail);
                            $retstr[] = $resEmail;
                            //FIXME: Seems like a break; here could save some ressources here
                        } elseif ($key == 'name') {
                            $resName = $hidden;
                        }
                    }
                }

                if (!(Response::where('response_id', $singleResponse['response_id'])->exists())) {
                    // save into our model
                    // see https://stackoverflow.com/questions/53793841/add-title-to-fillable-property-to-allow-mass-assignment-on-app-post

                    $jsonItem = json_encode($singleResponse, true);
                    if ($finalResponse = Response::create([
                        'email' => $resEmail,
                        'name' => $resName,
                        'json' => $jsonItem,
                        'response_id' => $singleResponse['response_id'],
                        'typeform_id' => $singleForm->typeform_id,
                        'time_requested' => $time_requested
                    ])) {
                        Log::debug('response saved');
                    } else {
                        Log::debug('could not save response');
                    }

                } else {
                    Log::debug('duplicate response found');
                }

            }
        }
        Log::debug("ResponseCron out:");
        return true;
        //
    }
}
