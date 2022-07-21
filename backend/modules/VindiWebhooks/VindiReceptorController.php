<?php

namespace VindiWebhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class VindiReceptorController extends Controller
{
    /**
     * chose te funcionality of system to call
     * @param Request $request
     * @return  \Illuminate\Http\Response
     */
    public function vindiReceptor(Request $request)
    {
        $traceError = null;
        $errorMessage = null;
        $error = null;
        try{
            $eventType = $request['event']['type'];
            switch ($eventType) {
                case 'bill_paid':
                    SubscriptionWebhookService::payedSubscription($request["event"]["data"]);
                    break;
            }
        } catch (\Throwable $error) {
            $errorMessage = substr($error->getMessage(), 0, 1000);
            $traceError = json_encode($error->getTrace());
            $error = $error;
        }
        if ($error)
            throw $error;
    }

}
