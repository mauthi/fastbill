<?php

namespace Fastbill\Resources;

use Fastbill\Api\Connection;

/**
 * Class Articles
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Articles extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'article';
    const FASTBILL_RESOURCE = 'ARTICLES';
    const FASTBILL_RESOURCE_KEY = 'ARTICLE_ID';

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE, self::FASTBILL_RESOURCE_KEY);
    }

}