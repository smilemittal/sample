<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CreateTypeFormWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $formId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($formId)
    {
        $this->formId = $formId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::withHeaders([
            'Authorization' => 'Bearer '.env('TF_TOKEN'),
            'Content-Type' => 'application/json'
        ])->put("https://api.typeform.com/forms/".$this->formId."/webhooks/" . env('APP_ENV'), [
            'url' => route('tf.webhook'),
            'enabled' => true,
            'secret' => env('TF_SECRET'),
            'verify_ssl' => true
        ]);
    }
}
