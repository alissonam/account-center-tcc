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
        $eventType = $request['event']['type'];
        switch ($eventType) {
            case 'bill_paid':
                SubscriptionWebhookService::payedSubscription($request["event"]["data"]);
                break;
        }
    }

}
