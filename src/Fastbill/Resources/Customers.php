<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;

/**
 * Class Customers
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Customers extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'customer';
    const FASTBILL_RESOURCE = 'CUSTOMERS';
    const FASTBILL_RESOURCE_KEY = 'CUSTOMER_ID';

    /**
     * @param Connection $connection
     */
    public function __construct(Fastbill $connection)
    {
        parent::__construct($connection, self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE, self::FASTBILL_RESOURCE_KEY);
    }
}