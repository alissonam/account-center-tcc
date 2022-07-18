<?php
namespace App\Services\ApiVindiService;

use Vindi\Vindi;

class BillService extends ApiVindiService {

    public function __construct()
    {
        parent::__construct("Bill");
    }

    public function getBillByIdVindi($idVindi)
    {
        return $this->vindiService->get($idVindi);
    }

    public function updateBill($idBill,$bill)
    {
        return $this->vindiService->update($idBill, $bill);
    }
}
