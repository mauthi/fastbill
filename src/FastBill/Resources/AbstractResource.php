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
     * @return string
     */
    public function getAll()
    {
        return $this->request($this->_service);
    }


    private function request($service) {
        $result = $this->_connection->request(array('SERVICE' => $service));
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