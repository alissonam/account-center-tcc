<?php
namespace VindiGatway;

use Users\User;
use Vindi\Vindi;

class CustomerVindiService extends ApiVindiService {

    private $accountCenterUser = null;

    public function __construct($accountCenterUser = null)
    {
        parent::__construct("Customer");
        $this->accountCenterUser = $accountCenterUser;
    }

    public function getCustomerByPaymentGatwayId($idCustomer)
    {
        $customer = $this->vindiService->get($idCustomer);
        return $this->customerToAccountCenterUser($customer);
    }

    public function storeCustomer()
    {
        $customer = $this->accountCenterToCustomer();
        $storedVindiCustomer = $this->vindiService->create($customer);
        $this->accountCenterUser->vindi_id = $storedVindiCustomer->id;
        return $this->accountCenterUser;
    }

    public function updateCustomer()
    {
        $customer = $this->accountCenterToCustomer();
        $this->vindiService->update($customer['id'], $customer);
        return $this->accountCenterUser;
    }

    public function archiveCustomer()
    {
        $this->vindiService->delete($this->accountCenterUser->vindi_id);
    }

    public function unarchiveCustomer()
    {
        $this->vindiService->unarchive($this->accountCenterUser->vindi_id);
    }

    private function customerToAccountCenterUser($customer)
    {
        $user = new User(
            [
            'id' => $customer->code,
            'name' => $customer->name,
            'email' => $customer->email,
            'document' => $customer->registry_code,
            'status' => $customer->status,
            'zipcode' => $customer->address->zipcode,
            'street' => $customer->address->street,
            'number' => $customer->address->number,
            'complement' => $customer->address->additional_details,
            'neighborhood' => $customer->address->neighborhood,
            'city' => $customer->address->city,
            'state' => $customer->address->state,
            'vindi_id' => $customer->id
        ]);

        return $user;
    }

    private function accountCenterToCustomer() {
        $customer = [
            'id'            => $this->accountCenterUser->vindi_id ?? null,
            'name'          => $this->accountCenterUser->name.$this->accountCenterUser->last_name,
            'email'         => $this->accountCenterUser->email,
            'code'          => $this->accountCenterUser->id,
            'registry_code' => $this->accountCenterUser->document,
            'status'        => $this->accountCenterUser->status
        ];

        $customerAddress = [
            "zipcode"            => $this->accountCenterUser->zipcode,
            "street"             => $this->accountCenterUser->street,
            "number"             => $this->accountCenterUser->number,
            "additional_details" => $this->accountCenterUser->complement,
            "neighborhood"       => $this->accountCenterUser->neighborhood,
            "city"               => $this->accountCenterUser->city,
            "state"              => $this->accountCenterUser->state,
            "country"            => 'BR'
        ];

        $customer['address'] = $customerAddress;
        return array_filter($customer);
    }

    private function mountVindiPhone($phoneNumber)
    {
        $customerPhone =[];
        $customerPhone[0]['phone_type'] = 'mobile';
        $customerPhone[0]['number']     = '55'.$phoneNumber;
        return $customerPhone;
    }
}
