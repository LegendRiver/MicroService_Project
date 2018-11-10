<?php
namespace FBBasicService\Manager;

use CommonMoudle\Constant\LogConstant;
use FacebookAds\Object\Page;
use FBBasicService\Common\FBLogger;

class PageManager
{
    private static $instance = null;

    private function __construct()
    {

    }

    public static function instance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function readPageById($pageId)
    {
        try
        {
            $page = new Page($pageId);
            $page->read(array(
                'id',
                'name',
                'username',
                'ad_campaign',
                'affiliation',
                'app_id',
                'can_post',
                'category',
                'is_published',
                'link',
                'description',
                ));
            print_r($page);
        }
        catch (\Exception $e)
        {
            FBLogger::instance()->writeExceptionLog(LogConstant::LOGGER_LEVEL_ERROR, $e);
            return false;
        }
    }
}