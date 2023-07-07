<?php

namespace App\Console\Commands;

use App\Models\Form;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TypeformCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:typeform-cron';

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
        Log::debug("TypeformCron in\n\n**********\n");
        $localForms = Form::get();

        foreach ($localForms as $local) {
            /*
                1. Only call the api if we don't have json
                2. Update the local form with the data from the api
            */
            // 1. check for json

            if (!$local->json) {
                Log::debug("Form did not have json");
                $response = Http::withToken('HK5cFuhtjbN9wMV19YFqG7K76MF98qqTPA8QEzNZSSJd')
                ->get("https://woofeculture.typeform.com/forms/$local->typeform_id");
                $res = $response->json();
                $local->json = json_encode($res, true);
                $local->save();
            }

        }

        Log::debug("\n**********\nTypeformCron out\n");
        //
    }
}
