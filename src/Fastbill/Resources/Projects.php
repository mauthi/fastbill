<?php

namespace Fastbill\Resources;

use Fastbill\Api\Connection;

/**
 * Class Projects
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Projects extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'project';
    const FASTBILL_RESOURCE = 'PROJECTS';
    const FASTBILL_RESOURCE_KEY = 'PROJECT_ID';

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE, self::FASTBILL_RESOURCE_KEY);
    }
}