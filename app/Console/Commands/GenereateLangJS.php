<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenereateLangJS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xme:generate_lang_js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate the language js files.';

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
     * @return int
     */
    public function handle()
    {
        $languages = ['en'];
        foreach ($languages as $language) {
            $files = glob(resource_path('lang/' . $language . '/*.php'));
            $strings = [];
            foreach ($files as $file) {
                $name = basename($file, '.php');
                if ($name !== "lang") {
                    $new_keys = require $file;
                    $strings = array_merge($strings, [$name => $new_keys]);
                }
            }
            $content = 'window.i18n = ' . json_encode(array("lang" => $strings));
            $filename = $language.'.js';
            $dir = resource_path('js/lang');
            if (! \File::isDirectory($dir)) {
                \File::makeDirectory($dir, 493, true);
            }

            $file = $dir.'/'.$filename;
            $handle = fopen($file, 'w');
            fwrite($handle, $content);
            fclose($handle);
            $this->info($filename.' File Created.');
        }
        return 0;
    }
}
