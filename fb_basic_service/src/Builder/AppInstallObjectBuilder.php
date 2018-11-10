<?php
namespace FBBasicService\Builder;

use CommonMoudle\Constant\LogConstant;
use FacebookAds\Object\Fields\AdPromotedObjectFields;
use FBBasicService\Common\FBLogger;

class AppInstallObjectBuilder implements IFieldBuilder
{
    private $applicationId;

    private $applicationStoreUrl;

    private $outputArray = array();

    public function __construct()
    {
    }

    public function getOutputField()
    {
        $this->outputArray = array();
        if(isset($this->applicationId) && isset($this->applicationStoreUrl))
        {
            $this->outputArray[AdPromotedObjectFields::APPLICATION_ID] = $this->applicationId;
            $this->outputArray[AdPromotedObjectFields::OBJECT_STORE_URL] = $this->applicationStoreUrl;
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_ERROR, 'Failed to builder application Object.');
        }

        return $this->outputArray;
    }

    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function setApplicationStoreUrl($applicationStoreUrl)
    {
        $this->applicationStoreUrl = $applicationStoreUrl;
    }


}