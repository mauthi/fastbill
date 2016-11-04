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
    const SUCCESS = "success";
    private $_connection;
    private $_service;
    private $_resource;
    private $_resourceKey;

    /**
     * AbstractResource constructor.
     * @param Connection $connection
     */
    public function __construct(Fastbill $connection, $service, $resource, $resourceKey)
    {
        $this->_connection = $connection;
        $this->_service = $service;
        $this->_resource = $resource;
        $this->_resourceKey = $resourceKey;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $limit = self::FASTBILL_LIMIT;
        $offset = 0;
        $result = array();
        $service = $this->_service.".get";
        $resource = $this->_resource;

        $loopCount = 0;
        do {
            $loopCount++;
            $limitOffsetResult = $this->getResultOrDefaultValue($this->getRequest($service, $limit, $offset), $resource, array());
            //echo "LoopCount {$loopCount}: Limit: $limit / Offset: $offset / Size: ".sizeof($limitOffsetResult);
            $result = array_merge($result,$limitOffsetResult);
            $offset += $limit;
        } while (sizeof($limitOffsetResult) > 0 && sizeof($limitOffsetResult) == $limit);

        return $result;
    }

    /**
     * @return array
     */
    public function getOne($id) {
        $service = $this->_service.".get";
        $resource = $this->_resource;
        $filter = array();
        $filter[$this->_resourceKey] = $id;

        $this->getResultOrDefaultValue($this->getRequest($service, 1, 0, $filter), $resource, false);
    }

    /**
     * @return array Array of created resource or false
     */
    public function create($data)
    {
        $service = $this->_service.".create";
        $result = $this->postRequest($service, $data);

        if ($this->getResultOrDefaultValue($result,"STATUS") == self::SUCCESS && $id = $this->getResultOrDefaultValue($result,$this->_resourceKey)) {
            return $this->getOne($id);
        } 
        return false;
    }

    /**
     * @return array Array of updated resource or false
     */
    public function update($id, $data)
    {
        $data[$this->_resourceKey] = $id;
        $service = $this->_service.".update";
        $result = $this->postRequest($service, $data);

        if ($this->getResultOrDefaultValue($result,"STATUS") == self::SUCCESS && $id = $this->getResultOrDefaultValue($result,$this->_resourceKey)) {
             return $this->getOne($id);
        } 
        return false;
    }

    /**
     * @param array $data
     * @return string 
     */
    public function updateOrCreate($id, array $data) {
        if (is_null($id))
            return $this->create($data);
        else
            return $this->update($id, $data);
    }


    protected function getResultOrDefaultValue($result, $key, $defaultValue = false) {
        if (isset($result["RESPONSE"][$key]))
            return $result["RESPONSE"][$key];

        return $defaultValue;
    }


    private function getRequest($service, $limit = self::FASTBILL_LIMIT, $offset = 0, $filter = array()) {
        $requestData = array('SERVICE' => $service, 'LIMIT' => $limit, 'OFFSET' => $offset, 'FILTER' => $filter);
        return $this->request($requestData);
    }

    private function postRequest($service, $data) {
        $requestData = array('SERVICE' => $service, 'DATA' => $data);
        return $this->request($requestData);
    }

    private function request($requestData) {
        $result = $this->_connection->request($requestData);
        if (isset($result["RESPONSE"]["ERRORS"]))
            throw new FastbillException("Error in Fastbill Request\nRequest: ".print_r($requestData,true)."\nResult: ".print_r($result,true));
        
        return $result;
    }
}