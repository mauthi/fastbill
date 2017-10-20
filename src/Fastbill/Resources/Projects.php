<?php

namespace Fastbill\Resources;

use Fastbill\Fastbill;

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
    public function __construct(Fastbill $connection)
    {
        parent::__construct($connection, self::FASTBILL_SERVICE, self::FASTBILL_RESOURCE, self::FASTBILL_RESOURCE_KEY);
    }


    /**
     * @return array
     */
    public function getAllByCustomerId($customerId) {
        // helper because fastbill get with filter id doesnt work
        return $this->getAll([
            "CUSTOMER_ID" => $customerId
        ]);
    }

    /**
     * @return array
     */
    public function getOneByCustomerId($customerId, $projectId) {
        // helper because fastbill get with filter id doesnt work
        $aProjects = $this->getAllByCustomerId($customerId);
        foreach ($aProjects as $aProject) {
            if ($aProject["PROJECT_ID"] == $projectId)
                return $aProject;
        }
        
        throw new FastbillException("getOneByCustomerId: Resource with ".$this->_resourceKey." = {$projectId} not found in Fastbill (Service: {$service})\nResponse: ".print_r($response,true));
    }


    /**
     * @return array Array of created resource or false
     */
    public function create(array $data)
    {
        // helper because fastbill get with filter id doesnt work
        $service = $this->_service.".create";
        $result = $this->postRequest($service, $data);

        if ($this->getResultOrDefaultValue($result,"STATUS") == self::SUCCESS && $id = $this->getResultOrDefaultValue($result,$this->_resourceKey)) {
            return $this->getOneByCustomerId($data["CUSTOMER_ID"], $id);
        } 
        return false;
    }

    /**
     * @return array Array of updated resource or false
     */
    public function update($id, array $data)
    {
        // helper because fastbill get with filter id doesnt work
        $data[$this->_resourceKey] = $id;
        $service = $this->_service.".update";
        $result = $this->postRequest($service, $data);

        if ($this->getResultOrDefaultValue($result,"STATUS") == self::SUCCESS && $id = $this->getResultOrDefaultValue($result,$this->_resourceKey)) {
             return $this->getOneByCustomerId($data["CUSTOMER_ID"], $id);
        } 
        return false;
    }

}