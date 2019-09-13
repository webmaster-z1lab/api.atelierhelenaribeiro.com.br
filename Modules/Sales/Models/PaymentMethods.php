<?php

namespace Modules\Sales\Models;

abstract class PaymentMethods
{
    public const MONEY = 'money';
    public const CHECK = 'check';
    public const CREDIT_CARD = 'credit_card';
}
