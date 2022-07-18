<?php
namespace VindiGetway;

use Vindi;

class ApiVindiService {

    private $vindiApiKey = null;
    private $vindiApiUri = null;
    protected $moduleName;
    protected $vindiService = null;

    public function __construct($moduleName)
    {
        $this->vindiApiKey = env('VINDI_API_KEY');
        $this->vindiApiUri = env('VINDI_API_URI');
        $this->moduleName = $moduleName;
        $this->autenticate();
    }

    private function autenticate(){
        $arguments = [
            'VINDI_API_KEY' => $this->vindiApiKey,
            'VINDI_API_URI' => $this->vindiApiUri,
        ];

        $vindiModule = "Vindi\\".$this->moduleName;
        $this->vindiService = new $vindiModule($arguments);
    }

}
