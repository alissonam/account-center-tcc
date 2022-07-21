<?php
namespace VindiGatway;

use Vindi\Vindi;

class BillService extends ApiVindiService {

    public function __construct()
    {
        parent::__construct("Bill");
    }

    public function getBillByIdVindi($vindi_id)
    {
        return $this->vindiService->get($vindi_id);
    }

    // public function updateBill($idBill,$bill)
    // {
    //     return $this->vindiService->update($idBill, $bill);
    // }
}
