<?php
namespace App\Core\Wrappers\Payment;

use App\Core\Wrappers\Payment\Abstracts\GatewayAbstract;
use App\Core\Wrappers\Payment\Drivers\Stripe;

class Gateway extends GatewayAbstract
{

    public function __construct($gateway)
    {
        $this->validateGateway($gateway);
    }
    
    public function card($card = [],$generateToken = false){
        try {
            $this->gateway->setCard($card,$generateToken);

            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function token($card = []){
        try {
            $this->gateway->setToken($card,true);
            
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function currency($currency){
        $this->gateway->setCurrency($currency);
        return $this;
    }

    public function getCharge(){
        return $this->gateway->getCharge();
    }
    public function isSuccessfull(){
            $charge = $this->getCharge();
            if($charge)
                return true;
                
            return false;
    }
    public function pay($amount = 0,$description = null){
        
        try {
            $this->gateway->handle($amount ,$description ); 
           
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

?>