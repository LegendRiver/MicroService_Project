<?php
namespace CommonMoudle\Helper;

use CommonMoudle\Constant\CommonConstant;

class DateHelper
{
    public static function getDeltaDate($days, $format = 'Y-m-d')
    {
        $timeStr = $days . " day";
        return date($format,strtotime($timeStr));
    }

    public static function getYesterdayDate($format = 'Y-m-d')
    {
        return date($format,strtotime("-1 day"));
    }
    public static function getTodayDate($format = 'Y-m-d')
    {
        return date($format);
    }
    public static function getTomorrowDate($format = 'Y-m-d')
    {
        return date($format,strtotime("+1 day"));
    }
    public static function getCurrentDateTime()
    {
        return strtotime(date('Y-m-d'));
    }

    public static function getCurrentTimeStamp()
    {
        return strtotime(date(CommonConstant::DATE_DEFAULT_FORMAT));
    }

    public static function getDayCountBetweenDate(\DateTime $startDate, \DateTime $endDate)
    {
        $interval = date_diff($startDate, $endDate);
        $dayCount = $interval->d + 1;
        return $dayCount;
    }

    public static function checkDateStrValid($dateStr)
    {
        if(!is_string($dateStr))
        {
            return false;
        }

        if(0 == strlen($dateStr))
        {
            return false;
        }

        list($y,$m,$d)=explode('-',$dateStr);
        return checkdate($m,$d,$y);
    }

    public static function getWeekdayByDate(\DateTime $date)
    {
        return date('w', $date->getTimestamp());
    }

    public static function getWeekdaysBetweenDate(\DateTime $startDate, \DateTime $endDate)
    {
        $startTime = $startDate->getTimestamp();
        $endTime = $endDate->getTimestamp();
        if($startTime > $endTime)
        {
            return array();
        }

        $dayCount = self::getDayCountBetweenDate($startDate, $endDate);
        if($dayCount >= 7)
        {
            return range(0,6);
        }

        $startWeekday = self::getWeekdayByDate($startDate);
        $endWeekday = self::getWeekdayByDate($endDate);
        if($startWeekday <= $endWeekday)
        {
            return range($startWeekday, $endWeekday);
        }
        else
        {
            $tmpArray = range($startWeekday, $endWeekday+7);
            $func = function($weekday)
            {
                return $weekday%7;
            };

            return array_map($func, $tmpArray);
        }

    }

    public static function getDateListBetweenDate($startDate, $endDate, $dateFormat='Y-m-d')
    {
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);

        $dateList = array();
        for($temTime = $startTime; $temTime <= $endTime; $temTime+=86400)
        {
            $dateStr = date($dateFormat, $temTime);
            $dateList[] = $dateStr;
        }

        return $dateList;
    }

    public static function dateFormatConvert($dateFormat, $dateString)
    {
        $timeStamp = strtotime($dateString);
        return date($dateFormat, $timeStamp);
    }
}