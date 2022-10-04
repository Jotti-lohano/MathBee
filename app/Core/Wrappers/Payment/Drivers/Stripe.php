<?php

namespace App\Core\Wrappers\Payment\Drivers;

use \Stripe\StripeClient;

class Stripe
{

    public $tokenConfiguration = [
        'card' => [
            'number', 'exp_month', 'exp_year', 'cvc' => '314',
        ],
    ];
    private $source;
    private $token;
    private $stripe;
    private $currency = 'usd';
    private $charge = null;

    public function __construct($credentials = [])
    {
        try {
            $this->stripe = new StripeClient(
                $credentials['private_key']
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setCard($card, $generateToken = true)
    {
        $this->source = $card;
        if ($generateToken) {
            $this->generateToken($card);
        }
    }

    public function setToken($card)
    {
        $this->generateToken($card);
    }

    public function generateToken($card = [])
    {
        try {
            $token = $this->stripe->tokens->create($card);
            $this->source = $token->id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function setCurrency($currency = 'usd')
    {
        $this->currency = $currency;
    }

    public function getCharge()
    {
        return $this->charge;
    }

    public function handle($amount, $description = '')
    {

        try {
            $this->charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => $this->currency,
                'source' => $this->source,
                'description' => $description,
            ]);
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
