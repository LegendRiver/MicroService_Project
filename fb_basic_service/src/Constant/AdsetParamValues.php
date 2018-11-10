<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 上午10:13
 */

namespace FBBasicService\Constant;


class AdsetParamValues
{
    const ADSET_PACE_TYPE_STANDARD = 'standard';
    //加速投放，只有最大值手动竞标时有效
    const ADSET_PACE_TYPE_NOPACE = 'no_pacing';
    const ADSET_PACE_TYPE_DAYPARTING = 'day_parting';

    const PUBLISHER_PLATFORM_FACEBOOK = 'facebook';
    const PUBLISHER_PLATFORM_INSTAGRAM = 'instagram';
    const PUBLISHER_PLATFORM_AUDIENCENETWORK = 'audience_network';

    const STORY_LINK_DATA = 1;
    const STORY_OFFER_DATA = 2;
    const STORY_PHOTO_DATA = 3;
    const STORY_TEMPLATE_DATA = 4;
    const STORY_TEXT_DATA = 5;
    const STORY_VIDEO_DATA = 6;

    const ADSET_BUDGET_TYPE_DAILY = 0;
    const ADSET_BUDGET_TYPE_SCHEDULE = 1;

    const ADSET_BILL_EVENT_IMPRESSIONS = 0;
    const ADSET_BILL_EVENT_LINKCLICK = 1;
    const ADSET_BILL_EVENT_APPINSTALL = 2;

    const DELIVERY_TYPE_STANDARD = 0;
    const DELIVERY_TYPE_ACCELERATE = 1;

    const SCHEDULE_TYPE_ALLTIMES = 0;
    const SCHEDULE_TYPE_SCHEDULE = 1;

    const ADSET_OPTIMIZATION_APPINSTALL = 0;
    const ADSET_OPTIMIZATION_LINKCLICK = 1;
    const ADSET_OPTIMIZATION_OFFSITE_CONVERSION = 2;
    const ADSET_OPTIMIZATION_PAGE_LIKE = 3;
    const ADSET_OPTIMIZATION_BRAND_AWARENESS = 4;
    const ADSET_OPTIMIZATION_REACH= 5;

}