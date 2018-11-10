<?php
namespace FBBasicService\Util;

use CommonMoudle\Constant\LogConstant;
use FBBasicService\Common\FBLogger;
use FBBasicService\Constant\InsightExportConstant;

class InsightValueReader
{
    public static function readInsightValue($insightData, $configInfo, $entities = array(), $manualValueMap = array())
    {
        $insightValue = array();
        foreach($configInfo as $infoKey=>$infoContent)
        {
            $value = static::getInsightByConf($infoKey, $infoContent, $insightData, $entities, $manualValueMap);
            if(is_array($value))
            {
                $insightValue = array_merge($insightValue, $value);
            }
            else
            {
                $insightValue[] = $value;
            }
        }

        return $insightValue;
    }

    public static function getInsightByConf($infoKey, $infoContent, $insightData, $entities = array(),
                                            $manualValueMap = array())
    {
        if(array_key_exists(InsightExportConstant::INSIGHT_INFO_REAL_KEY, $infoContent))
        {
            $realKey = $infoContent[InsightExportConstant::INSIGHT_INFO_REAL_KEY];
            if(!empty($realKey))
            {
                $infoKey = $realKey;
            }
        }

        $keyType = $infoContent[InsightExportConstant::INSIGHT_INFO_TYPE];
        if($keyType == InsightExportConstant::INSIGHT_FIELD_TYPE_LIST)
        {
            return static::getListInfoTypeValue($infoKey, $infoContent, $insightData);
        }
        else if($keyType == InsightExportConstant::INSIGHT_FIELD_TYPE_MAP)
        {
            return static::getMapInfoTypeValue($infoKey, $infoContent, $insightData);
        }
        else if($keyType == InsightExportConstant::INSIGHT_FIELD_FROM_ENTITY)
        {
            return static::getEntityValue($infoContent, $entities);
        }
        else if($keyType == InsightExportConstant::INSIGHT_FIELD_MANUAL)
        {
            return static::getManualValue($infoKey, $manualValueMap);
        }
        else
        {
            return static::getNormalValue($infoKey, $insightData);
        }
    }

    public static function generateInsightTitle($insightInfoConfig, $isAlias=true)
    {
        $csvTitle = array();
        foreach ($insightInfoConfig as $insightKey=>$keyConfig)
        {
            $titleName = static::getTitleByConf($insightKey, $keyConfig, $isAlias);
            if(is_array($titleName))
            {
                $csvTitle = array_merge($csvTitle, $titleName);
            }
            else
            {
                $csvTitle[] = $titleName;
            }
        }

        return $csvTitle;
    }

    public static function getTitleByConf($insightKey, $keyConfig, $isAlias=true)
    {
        if(array_key_exists(InsightExportConstant::INSIGHT_INFO_REAL_KEY, $keyConfig))
        {
            $realKey = $keyConfig[InsightExportConstant::INSIGHT_INFO_REAL_KEY];
            if(!empty($realKey))
            {
                $insightKey = $realKey;
            }
        }
        $keyType = $keyConfig[InsightExportConstant::INSIGHT_INFO_TYPE];
        if($keyType == InsightExportConstant::INSIGHT_FIELD_TYPE_LIST)
        {
            $displayKeys = $keyConfig[InsightExportConstant::INSIGHT_INFO_DISPLAY_KEYS];
            if($isAlias)
            {
                $displayKeys = self::getListInfoAlias($keyConfig);
            }
            return $displayKeys;
        }
        else
        {
            if($isAlias && array_key_exists(InsightExportConstant::INSIGHT_INFO_ALIAS, $keyConfig))
            {
                $aliasKey = $keyConfig[InsightExportConstant::INSIGHT_INFO_ALIAS];
                return $aliasKey;
            }
            else
            {
                return $insightKey;
            }
        }
    }

    public static function buildInstallConfig()
    {
        $detailConf = array(
            InsightExportConstant::INSIGHT_INFO_TYPE => InsightExportConstant::INSIGHT_FIELD_TYPE_LIST,
            InsightExportConstant::INSIGHT_INFO_ALIAS => array(
                "mobile_app_install" => "install",
            ),
            InsightExportConstant::INSIGHT_INFO_DISPLAY_KEYS => array("mobile_app_install"),
            InsightExportConstant::INSIGHT_INFO_VALUE_KEY => "value",
            InsightExportConstant::INSIGHT_INFO_KEY_KEY => "action_type",
        );

        return array("actions" => $detailConf);
    }

    public static function buildCPIConfig()
    {
        $detailConf = array(
            InsightExportConstant::INSIGHT_INFO_TYPE => InsightExportConstant::INSIGHT_FIELD_TYPE_LIST,
            InsightExportConstant::INSIGHT_INFO_ALIAS => array(
                "mobile_app_install" => "cost_per_install",
            ),
            InsightExportConstant::INSIGHT_INFO_DISPLAY_KEYS => array("mobile_app_install"),
            InsightExportConstant::INSIGHT_INFO_VALUE_KEY => "value",
            InsightExportConstant::INSIGHT_INFO_KEY_KEY => "action_type",
        );

        return array("cost_per_action_type" => $detailConf);
    }

    public static function buildSpendConfig()
    {
        $spendConf = array(
            "spend" => array(
                InsightExportConstant::INSIGHT_INFO_TYPE => InsightExportConstant::INSIGHT_FIELD_TYPE_VALUE,
            ),
        );

        return $spendConf;
    }

    public static function buildValueConfig($key)
    {
        $valueConf = array(
            $key => array(
                InsightExportConstant::INSIGHT_INFO_TYPE => InsightExportConstant::INSIGHT_FIELD_TYPE_VALUE,
            ),
        );

        return $valueConf;
    }

    private static function getListInfoAlias($keyConfig)
    {
        $displayKeys = $keyConfig[InsightExportConstant::INSIGHT_INFO_DISPLAY_KEYS];
        $aliasMap = $keyConfig[InsightExportConstant::INSIGHT_INFO_ALIAS];

        $replaceKeys = array();
        foreach($displayKeys as $displayKey)
        {
            if(array_key_exists($displayKey, $aliasMap))
            {
                $replaceKeys[] = $aliasMap[$displayKey];
            }
            else
            {
                $replaceKeys[] = $displayKey;
            }
        }

        return $replaceKeys;
    }

    private static function getManualValue($infoKey, $manualValueMap)
    {
        if(array_key_exists($infoKey, $manualValueMap))
        {
            return $manualValueMap[$infoKey];
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[manual]There is no key : ' . $infoKey . PHP_EOL);
            return '';
        }
    }

    private static function getNormalValue($infoKey, $insightData)
    {
        if(array_key_exists($infoKey, $insightData))
        {
            return $insightData[$infoKey];
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[value]There is no key : ' . $infoKey . PHP_EOL);
            return '';
        }
    }

    private static function getListInfoTypeValue($infoKey, $infoContent, $insightArray)
    {
        $displayKeys = $infoContent[InsightExportConstant::INSIGHT_INFO_DISPLAY_KEYS];
        $allSubValues = static::getAllListValues($infoKey, $infoContent, $insightArray);

        $valueList = array();
        foreach($displayKeys as $dKey)
        {
            if(array_key_exists($dKey, $allSubValues))
            {
                $valueList[] = $allSubValues[$dKey];
            }
            else
            {
                FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[sublist]There is no key : ' . $dKey . PHP_EOL);
                $valueList[] = '';
            }
        }

        return $valueList;
    }

    private static function getAllListValues($infoKey, $infoContent, $insightArray)
    {
        $allSubValues = array();
        if(array_key_exists($infoKey, $insightArray))
        {
            $dataList = $insightArray[$infoKey];
            foreach($dataList as $oneData)
            {
                $subKey = $infoContent[InsightExportConstant::INSIGHT_INFO_KEY_KEY];
                $subValue = $infoContent[InsightExportConstant::INSIGHT_INFO_VALUE_KEY];
                $dataKey = $oneData[$subKey];
                if(array_key_exists($subValue, $oneData))
                {
                    $dataValue = $oneData[$subValue];
                }
                else
                {
                    $dataValue = '';
                }

                $allSubValues[$dataKey] = $dataValue;
            }
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[list]There is no key: ' . $infoKey . PHP_EOL);
        }

        return $allSubValues;
    }

    private static function getMapInfoTypeValue($infoKey, $infoContent, $insightArray)
    {
        if(array_key_exists($infoKey, $insightArray))
        {
            $mapValueKey = $infoContent[InsightExportConstant::INSIGHT_INFO_VALUE_KEY];
            $subMap = $insightArray[$infoKey];
            if(array_key_exists($mapValueKey, $subMap))
            {
                $mapValue = $subMap[$mapValueKey];
            }
            else
            {
                FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[subMap]There is no key : ' . $mapValueKey . PHP_EOL);
                $mapValue = '';
            }
        }
        else
        {
            FBLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_INFO, '[map]There is no key : ' . $infoKey . PHP_EOL);
            $mapValue = '';
        }

        return $mapValue;
    }

    private function getEntityValue($keyConfig, $entities)
    {
        $className = $keyConfig[InsightExportConstant::INSIGHT_INFO_ENTITY_NAME];
        $method = $keyConfig[InsightExportConstant::INSIGHT_INFO_METHOD];

        foreach($entities as $nodeEntity)
        {
            if($className == get_class($nodeEntity))
            {
                $resultValue = call_user_func_array(array($nodeEntity, $method), array());
                return $resultValue;
            }
        }
        return '';
    }
}