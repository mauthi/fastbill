<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;

/**
 * Class Articles
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
class Articles extends AbstractResource implements ResourceInterface
{
    const FASTBILL_SERVICE = 'article.get';
    const FASTBILL_RESOURCE = 'ARTICLES';

	/**
     * @return string
     */
    public function getAll()
    {
        // $newUri = null;

        // $newUri = '?' . http_build_query(array('updated_since' => $this->_appendUpdatedSinceParam($updatedSince)));

        $this->_service = self::FASTBILL_SERVICE;
        $result = parent::getAll();
        if (isset($result["RESPONSE"][self::FASTBILL_RESOURCE]))
            return $result["RESPONSE"][self::FASTBILL_RESOURCE];

        return array();
    }

}