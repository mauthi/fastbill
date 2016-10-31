<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;

/**
 * Class Invoices
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Invoices extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'invoices.get';
    const FASTBILL_RESOURCE = 'INVOICES';

    /**
     * @return string
     */
    public function getAll()
    {
        // $newUri = null;

        // $newUri = '?' . http_build_query(array('updated_since' => $this->_appendUpdatedSinceParam($updatedSince)));

        $this->_service = self::FASTBILL_SERVICE;
        return parent::getAllForServiceAndResource(self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE);
    }

}