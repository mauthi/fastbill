<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;

/**
 * Class Invoices
 *
 * @namespace    Expenses\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Expenses extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'expense';
    const FASTBILL_RESOURCE = 'EXPENSES';
    const FASTBILL_RESOURCE_KEY = 'INVOICE_ID';

    /**
     * @param Connection $connection
     */
    public function __construct(Fastbill $connection)
    {
        parent::__construct($connection, self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE, self::FASTBILL_RESOURCE_KEY);
    }

}