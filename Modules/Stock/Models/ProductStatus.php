<?php

namespace Modules\Stock\Models;

abstract class ProductStatus
{
    public const AVAILABLE_STATUS      = 'available';
    public const IN_TRANSIT_STATUS     = 'in_transit';
    public const POSTED_STATUS         = 'posted';
    public const SOLD_STATUS           = 'sold';
    public const ON_CONSIGNMENT_STATUS = 'on_consignment';
    public const RETURNED_STATUS       = 'returned';
}
