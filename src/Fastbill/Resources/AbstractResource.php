<?php

namespace Fastbill\Resources;

use Fastbill\Api\Connection;
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
    protected $_connection;
    protected $_service;
    protected $_resource;
    protected $_resourceKey;

    /**
     * AbstractResource constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection, $service, $resource, $resourceKey)
    {
        $this->_connection = $connection;
        $this->_service = $service;
        $this->_resource = $resource;
        $this->_resourceKey = $resourceKey;
    }

    /**
     * @return array
     */
    public function getAll($filter = [])
    {
        $limit = self::FASTBILL_LIMIT;
        $offset = 0;
        $result = array();
        $service = $this->_service.".get";
        $resource = $this->_resource;

        $loopCount = 0;
        do {
            $loopCount++;
            $limitOffsetResult = $this->getResultOrDefaultValue($this->getRequest($service, $limit, $offset, $filter), $resource, array());
            //echo "LoopCount {$loopCount}: Limit: $limit / Offset: $offset / Size: ".sizeof($limitOffsetResult);
            $result = array_merge($result,$limitOffsetResult);
            $offset += $limit;
        } while (sizeof($limitOffsetResult) > 0 && sizeof($limitOffsetResult) == $limit);

        return $this->filterResult($result);
    }

    /**
     * @return array
     */
    public function getOne($id) {
        $service = $this->_service.".get";
        $resource = $this->_resource;
        $filter = array();
        $filter[$this->_resourceKey] = $id;

        $response = $this->getRequest($service, 1, 0, $filter);
        $result = $this->getResultOrDefaultValue($response, $resource, false);

        if (!isset($result[0]))
            throw new FastbillException("Resource with ".$this->_resourceKey." = {$id} not found in Fastbill (Service: {$service})\nResponse: ".print_r($response,true));

         if ($result[0][$this->_resourceKey] != $id)
            throw new FastbillException("Fastbill returned resource with wrong id (Service: {$service})\nResponse: ".print_r($response,true));

        return $this->filterResult($result[0]);
    }

    protected function filterResult($result) {
        array_walk_recursive($result, function(&$value) {
            $value = htmlspecialchars_decode($value);
        });
        return $result;
    }

    /**
     * @return array Array of created resource or false
     */
    public function create(array $data)
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
    public function update($id, array $data)
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


    protected function getRequest($service, $limit = self::FASTBILL_LIMIT, $offset = 0, $filter = array()) {
        $requestData = array('SERVICE' => $service, 'LIMIT' => $limit, 'OFFSET' => $offset, 'FILTER' => $filter);
        return $this->request($requestData);
    }

    protected function postRequest($service, $data) {
        $requestData = array('SERVICE' => $service, 'DATA' => $data);
        return $this->request($requestData);
    }

    protected function request($requestData) {
        $result = $this->_connection->request($requestData);
        if (isset($result["RESPONSE"]["ERRORS"]))
            throw new FastbillException("Error in Fastbill Request\nRequest: ".print_r($requestData,true)."\nResult: ".print_r($result,true));
        
        return $result;
    }
}