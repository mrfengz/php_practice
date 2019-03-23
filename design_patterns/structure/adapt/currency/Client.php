<?php
include_once 'EuroAdapter.php';
include_once 'DollarCalc.php';

class Client
{
    private $requestNow;
    private $dollarRequest;

    public function __construct()
    {
        $this->requestNow = new EuroAdapter();
        $this->dollarRequest = new DollarCalc();
        $euro = '&#8364;';
        echo "Euro: $euro" . $this->makeAdapterRequest($this->requestNow) . PHP_EOL;
        echo "Dollar: $" . $this->makeDollarRequest($this->dollarRequest) . PHP_EOL;
    }

    private function makeAdapterRequest(ITarget $req)
    {
        return $req->requestCalc(40, 50);
    }

    private function makeDollarRequest(DollarCalc $req)
    {
        return $req->requestCalc(40, 50);
    }
}

new Client();