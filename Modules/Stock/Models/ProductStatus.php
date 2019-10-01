<?php

namespace Modules\Stock\Models;

abstract class ProductStatus
{
    public const AVAILABLE_STATUS      = 'available';
    public const IN_TRANSIT_STATUS     = 'in_transit';
    public const SHIPPED_STATUS        = 'shipped';
    public const SOLD_STATUS           = 'sold';
    public const ON_CONSIGNMENT_STATUS = 'on_consignment';
    public const RETURNED_STATUS       = 'returned';
    public const AWAITING_STATUS       = 'awaiting';
    public const IN_PRODUCTION_STATUS  = 'in_production';
    public const READY_STATUS          = 'ready for shipping';
}
