<?php

namespace App\Stripe;

use Stripe\Charge;
use Stripe\Stripe;

/**
 * Class ChargeManager
 * @package App\Stripe
 */
class ChargeManager
{
    /**
     * @var string
     */
    private $stripeSecretKey;

    /**
     * ChargeManager constructor.
     * @param string $stripeSecretKey
     */
    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
    }

    /**
     * @param int $amount
     * @param string $token
     * @param string $description
     */
    public function create(int $amount, string $token, string $description)
    {
        Stripe::setApiKey($this->stripeSecretKey);

        Charge::create([
            'amount' => $this->stripeSecretKey,
            'currency' => 'eur',
            'description' => $description,
            'source' => $token,
        ]);
    }
}