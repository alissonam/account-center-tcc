<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class VindiController extends Controller
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
                    dd("BillPaid");
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
