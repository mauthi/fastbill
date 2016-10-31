<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;
use Fastbill\Exceptions\FastbillException;

/**
 * Class AbstractResource
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
abstract class AbstractResource
{
    const FASTBILL_LIMIT = 100;
    private $_connection;
    protected $_service;

    /**
     * AbstractResource constructor.
     * @param Connection $connection
     */
    public function __construct(Fastbill $connection)
    {
        $this->_connection = $connection;
        $this->_service = '';
    }

    /**
     * @param string $service
     * @param string $resource
     * @return array
     */
    public function getAllForServiceAndResource($service, $resource)
    {
        $limit = self::FASTBILL_LIMIT;
        $offset = 0;
        $result = array();

        $loopCount = 0;
        do {
            $loopCount++;
            $limitOffsetResult = $this->getResultOrEmptyArray($this->request($service, $limit, $offset), $resource);
            //echo "LoopCount {$loopCount}: Limit: $limit / Offset: $offset / Size: ".sizeof($limitOffsetResult);
            $result = array_merge($result,$limitOffsetResult);
            $offset += $limit;
        } while (sizeof($limitOffsetResult) > 0 && sizeof($limitOffsetResult) == $limit);

        return $result;
    }

    protected function getResultOrEmptyArray($result, $key) {
        if (isset($result["RESPONSE"][$key]))
            return $result["RESPONSE"][$key];

        return array();
    }


    private function request($service, $limit, $offset) {
        $result = $this->_connection->request(array('SERVICE' => $service, 'LIMIT' => $limit, 'OFFSET' => $offset));
        if (isset($result["RESPONSE"]["ERRORS"]))
            throw new FastbillException("Error in Fastbill Request\nResult: ".print_r($result,true));
        
        return $result;
    }

    // /**
    //  * @param string|DateTime $updatedSince
    //  * @return bool|string
    //  */
    // protected function _appendUpdatedSinceParam($updatedSince = null)
    // {
    //     if( is_null($updatedSince) ) {
    //         return false;
    //     } else if( $updatedSince instanceOf \DateTime ) {
    //         $updatedSince->setTimezone(new \DateTimeZone('Z')); // convert to correct harvest intern timezone
    //         return $updatedSince->format("Y-m-d G:i:s");
    //     } else {
    //         return $updatedSince;
    //     }
    // }
}