<?php
namespace FBBasicService\Manager;

use CommonMoudle\Constant\LogConstant;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\FBCommonConstant;
use FBBasicService\Constant\FBParamConstant;
use FBBasicService\Constant\FBParamValueConstant;
use FBBasicService\Entity\AdImageEntity;

class AdImageManager
{
    private static $instance = null;

    private $hash2ImageEntity;

    private $allFields;

    private $defaultFields;

    private $params;

    private function __construct()
    {
        $this->hash2ImageEntity = array();

        $this->defaultFields = array(
            AdImageFields::ID,
            AdImageFields::ACCOUNT_ID,
            AdImageFields::HASH,
            AdImageFields::HEIGHT,
            AdImageFields::WIDTH,
            AdImageFields::NAME,
            AdImageFields::STATUS,
            AdImageFields::URL,
        );

        $this->params = array(
            FBParamConstant::QUERY_PARAM_LIMIT => FBParamValueConstant::QUERY_COMMON_AMOUNT_LIMIT,
        );

        $this->initAllFields();
    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }
    public function createImageByByte($base64Byte, $accountId)
    {
        try
        {
            $image = new EliImage(null, $accountId);
            $image->{AdImageFields::BYTES} = $base64Byte;
            $image->createByBytes();

            $image->read($this->defaultFields);
            $imageEntity = $this->newAImage($image);

            return $imageEntity;
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }

    public function createImage($imagePath, $accountId)
    {
        try
        {
            $image = new AdImage(null, $accountId);
            $image->{AdImageFields::FILENAME} = $imagePath;
            $image->create();

            $image->read($this->defaultFields);
            $imageHash = $image->{AdImageFields::HASH};
            $imageEntity = $this->newAImage($image);
            $this->hash2ImageEntity[$imageHash] = $imageEntity;

            return $imageEntity;
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

    }

    public function createFromZip($zipPath, $accountId)
    {

    }

    public function deleteImageById($imageId, $imageHash, $accountId)
    {
        try
        {
            $adImage = new AdImage($imageId, $accountId);
            $adImage->{AdImageFields::HASH} = $imageHash;
            $adImage->delete();

            if(array_key_exists($imageHash, $this->hash2ImageEntity))
            {
                unset($this->hash2ImageEntity[$imageHash]);
            }
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }

    public function deleteImageByHashes(array $imageHashes, $accountId)
    {
        $imageEntities = $this->getImageInfoByHash($imageHashes, $accountId);
        foreach ($imageEntities as $image)
        {
            $imageHash = $image->getImageHash();
            $imageId = $image->getId();
            $this->deleteImageById($imageId, $imageHash, $accountId);
        }

        return $imageEntities;
    }

    public function getImageInfoByHash(array $imageHash, $accountId)
    {
        $resultArray = array();
        $noBufferHash = array();
        foreach($imageHash as $hash)
        {
            if(array_key_exists($hash, $this->hash2ImageEntity))
            {
                $resultArray[] = $this->hash2ImageEntity[$hash];
            }
            else
            {
                $noBufferHash[] = $hash;
            }
        }

        $images = $this->queryImageByhashes($noBufferHash, $accountId);

        return array_merge($resultArray, $images);
    }

    public function getAllImagesByAccount($accountId)
    {
        try
        {
            $arrayImage = array();
            $account = new AdAccount($accountId);
            $images = $account->getAdImages($this->defaultFields, $this->params);
            while($images->valid())
            {
                $currentImage = $images->current();
                $imageHash = $currentImage->{AdImageFields::HASH};

                $imageEntity = $this->newAImage($currentImage);
                $this->hash2ImageEntity[$imageHash] = $imageEntity;
                $arrayImage[] = $imageEntity;

                $images->next();
            }

            return $arrayImage;
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }

    private function queryImageByhashes(array $hashes, $accountId)
    {
        $arrayImage = array();
        if(empty($hashes))
        {
            return $arrayImage;
        }

        try
        {
            $account = new AdAccount($accountId);
            $param = array(FBParamConstant::ADIMAGE_PARAM_HASHES => $hashes);
            $images = $account->getAdImages($this->defaultFields, $param);
            while ($images->valid())
            {
                $currentImage = $images->current();
                $imageHash = $currentImage->{AdImageFields::HASH};
                $imageEntity = $this->newAImage($currentImage);

                if(!array_key_exists($imageHash, $this->hash2ImageEntity))
                {
                    $this->hash2ImageEntity[$imageHash] = $imageEntity;
                }

                $arrayImage[] = $imageEntity;
                $images->next();
            }

            return $arrayImage;
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }

    }

    private function newAImage($image)
    {
        $entity = new AdImageEntity();
        $entity->setId($image->{AdImageFields::ID});

        $accountId = FBCommonConstant::ADACCOUNT_ID_PREFIX . $image->{AdImageFields::ACCOUNT_ID};
        $entity->setAccountId($accountId);

        $entity->setFileName($image->{AdImageFields::NAME});
        $entity->setStatus($image->{AdImageFields::STATUS});
        $entity->setImageHash($image->{AdImageFields::HASH});
        $entity->setHeight($image->{AdImageFields::HEIGHT});
        $entity->setWidth($image->{AdImageFields::WIDTH});
        $entity->setUrl($image->{AdImageFields::URL});

        return $entity;
    }

    private function initAllFields()
    {
        $fields = new AdImageFields();
        $this->allFields = $fields->getValues();
    }

}