<?php

namespace App\Console\Commands;

use App\Jobs\CreateTypeFormWebhook;
use App\Models\Form;
use Illuminate\Console\Command;

class UpdateTFWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xme:update_tf_webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate to new webhook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $forms = Form::select('id')->get();
        foreach ($forms as $form) {
            dispatch(new CreateTypeFormWebhook($form->id));
        }
    }
}
