<?php

namespace Modules\Sales\Models;

abstract class PaymentMethods
{
    public const MONEY       = 'money';
    public const PAYCHECK    = 'paycheck';
    public const CREDIT_CARD = 'credit_card';
    public const BOLETO      = 'boleto';
}
