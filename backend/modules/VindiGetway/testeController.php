<?php

namespace VindiGetway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Users\User;

class testeController extends Controller
{

    public function __construct()
    {
    }

    public function teste(User $user)
    {

        $customerService = new CustomerService($user);
        // $customer = $customerService->storeCustomer();
        $customer = $customerService->archiveCustomer();
        dd("teste", $customer);
    }
}
