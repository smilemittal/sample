<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;

class InvoiceController extends Controller
{
    /**
     * Show the Invoice list
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user = $request->user()->company;
        $invoices = $user->invoices();

        $subscription = $user->subscription('default');
        $upcomingInvoice = null;
        if ($subscription && !$subscription->cancelled()) {
            $futureInvoice = $user->upcomingInvoice();
            if (!empty($futureInvoice)) {
                $upcomingInvoice = [
                    'date' => $futureInvoice->date()->toFormattedDateString(),
                    'total' => $futureInvoice->total(),
                ];
            }
        }

        $invoiceList = $invoices->map(function ($invoice) {
            return [
                'link' => route('invoices.show', ['invoice' => $invoice->id]),
                'download_link' => route('invoices.download', ['invoice' => $invoice->id]),
                'status' => $invoice->status,
                'total' => $invoice->total(),
                'date' => $invoice->date()->toFormattedDateString(),
            ];
        });

        return Inertia::render('Stripe/Invoices', compact('upcomingInvoice', 'invoiceList'));
    }

    /**
     * Show the Single Invoice
     *
     * @param  Request  $request
     * @return View
     */
    public function show(Request $request, $invoiceId)
    {
        $user = $request->user()->company;
        $invoice = $user->findInvoiceOrFail($invoiceId);
        $fromAddress = explode(',', config('app.billing_from_address'));

        $pdf = Pdf::loadView('invoice', compact('user', 'invoice', 'fromAddress'));
        return $pdf->stream('xme-invoice:' . $invoiceId . '.pdf');
    }

    /**
     * Show the Single Invoice
     *
     * @param  Request  $request
     * @return View
     */
    public function download(Request $request, $invoiceId)
    {
        $user = $request->user()->company;
        $invoice = $user->findInvoiceOrFail($invoiceId);
        $fromAddress = explode(',', config('app.billing_from_address'));

        $pdf = Pdf::loadView('invoice', compact('user', 'invoice', 'fromAddress'));
        return $pdf->download('xme-invoice:' . $invoiceId . '.pdf');
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  Request  $request
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request)
    {
        return response()->json(['message' => __('subscription.update_information')]);
    }
}
