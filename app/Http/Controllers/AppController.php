<?php

namespace App\Http\Controllers;

use App\Events\TypeFormWebhookReceived;
use App\Models\Attachement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Session;

class AppController extends Controller
{
    public function __construct()
    {
    }

    /**
     * set the locale for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function setLocale(Request $request)
    {
        try {
            $input = $request->all();
            $user = $request->user();
            $validator = Validator::make($request->all(), [
                'locale' => 'required|in:en,es'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()[0]]);
            }

            // if ($user) {
            //     $user->language = $input['locale'];
            //     $user->save();
            // } else {
                Session::put('locale', $input['locale']);
            //}

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * generate the language object and save into window.i18n namespace of the browser.
     *
     * @return Javascript
     */
    public function generateLangObject()
    {
        $lang = app()->getLocale();
        $files = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if ($name !== "lang") {
                $new_keys = require $file;
                $strings = array_merge($strings, [$name => $new_keys]);
            }
        }
        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode(array("lang" => $strings)) . ';');
        exit();
    }

    /**
     * Handle a Typeform webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        abort_unless($this->typeformSignatureMatches($request), 403);
        $payload = json_decode($request->getContent(), true);
     
        return TypeFormWebhookReceived::dispatch($payload);
    }

    protected function typeformSignatureMatches(Request $request)
    {
        $body = $request->getContent();
        
        $providedSignature = $request->header('Typeform-Signature');

        $actualSignature = 'sha256=' . base64_encode(hash_hmac('sha256', $body, env('TF_SECRET'), true));

        return $providedSignature === $actualSignature;
    }
}
