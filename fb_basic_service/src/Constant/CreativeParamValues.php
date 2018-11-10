<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/26
 * Time: 上午10:02
 */

namespace FBBasicService\Constant;


class CreativeParamValues
{
    const VIDEO_TYPE_COMMON = 1;
    const VIDEO_TYPE_SLIDESHOW = 0;

    const CREATIVE_TYPE_CAROUSEL_VIDEO = 'carousel_video';
    const CREATIVE_TYPE_CAROUSEL_IMAGE = 'carousel_image';
    const CREATIVE_TYPE_SINGLE_IMAGE = 'single_image';
    const CREATIVE_TYPE_SINGLE_VIDEO = 'single_video';

    const SLIDESHOW_TRANSITION_TIME_DEFAULT = 200;
    const SLIDESHOW_DURATION_TIME_DEFAULT = 1000;

    const LINK_AD_TYPE_CALLTOACTION = 1;
    const LINK_AD_TYPE_LINKDATA = 2;
    const LINK_AD_TYPE_NULL = 3;

    const STORY_LINK_DATA = 1;
    const STORY_OFFER_DATA = 2;
    const STORY_PHOTO_DATA = 3;
    const STORY_TEMPLATE_DATA = 4;
    const STORY_TEXT_DATA = 5;
    const STORY_VIDEO_DATA = 6;

    const CREATIVE_CALLTOACTION_MOBILEAPP_INSTALL = 0;
    const CREATIVE_CALLTOACTION_OPEN_LINK = 1;
    const CREATIVE_CALLTOACTION_DOWNLOAD = 2;
    const CREATIVE_CALLTOACTION_NOBUTTON = 3;
    const CREATIVE_CALLTOACTION_SHOPNOW = 4;
    const CREATIVE_CALLTOACTION_LEARNMORE = 5;
    const CREATIVE_CALLTOACTION_WATCHMORE = 6;
    const CREATIVE_CALLTOACTION_LIKEPAGE = 7;
    const CREATIVE_CALLTOACTION_PLAYGAME = 8;
    const CREATIVE_CALLTOACTION_INSTALLAPP = 9;
    const CREATIVE_CALLTOACTION_USEAPP = 10;
    const CREATIVE_CALLTOACTION_BUYNOW = 11;
}