<?php

namespace FBBasicService\Constant;

class FBParamConstant
{
    const QUERY_PARAM_LIMIT = 'limit';

    const ADIMAGE_PARAM_HASHES = 'hashes';
    const TIME_RANGE_SINCE = 'since';
    const TIME_RANGE_UNTIL = 'until';

    const AD_USER_DES_DEFAULT = 'me';
    const AD_CREATIVE_ID = 'creative_id';

    const SLIDE_SHOW_IMAGEURL = 'images_urls';
    const SLIDE_SHOW_DURATION = 'duration_ms';
    const SLIDE_SHOW_TRANSITION = 'transition_ms';

    const REACH_ESTIMATE_PARAM_CURRENCY = 'currency';
    const REACH_ESTIMATE_PARAM_DAILY_BUDGET = 'daily_budget';
    const REACH_ESTIMATE_PARAM_URL = 'object_store_url';
    const REACH_ESTIMATE_PARAM_OPTIMIZATION = 'optimize_for';
    const REACH_ESTIMATE_PARAM_TARGETING = 'targeting_spec';

    const REACH_ESTIMATE_BID_FIELD_MIN = 'min_bid';
    const REACH_ESTIMATE_BID_FIELD_MAX = 'max_bid';
    const REACH_ESTIMATE_BID_FIELD_MEDIAN = 'median_bid';
    const REACH_ESTIMATE_CPA_CURVE_DATA = 'cpa_curve_data';
    const REACH_ESTIMATE_DAU = 'estimate_DAU';
    const REACH_ESTIMATE_CURVE = 'curve';

    const DELIVERY_FIELD_BID = 'bid_estimate';
    const DELIVERY_FIELD_CURVE = 'daily_outcomes_curve';
    const DELIVERY_FIELD_DAU = 'estimate_dau';
    const DELIVERY_FIELD_MAU = 'estimate_mau';

    const LOCALE_SEARCH_KEY = 'key';
    const LOCALE_SEARCH_NAME = 'name';
    const APPLICATION_SEARCH_TYPE = 'addestination';
    const APPLICATION_SEARCH_PARA_URL = 'object_url';
    const APPLICATION_SEARCH_ID = 'id';
    const APPLICATION_SEARCH_NAME = 'name';
    const APPLICATION_SEARCH_ORIGINURL = 'original_object_url';
    const APPLICATION_SEARCH_STOREURL = 'object_store_url';
    const APPLICATION_SEARCH_PICTURE = 'picture';
    const APPLICATION_SEARCH_DESCRIPTION = 'description';
    const APPLICATION_SEARCH_SUPPORTDEVICE = 'supported_devices';

    const COUNTRY_SEARCH_PARAM = 'country';
    const CITY_SEARCH_PARAM = 'city';
    const REGION_SEARCH_PARAM = 'region';
    const COUNTRY_SEARCH_KEY = 'key';
    const COUNTRY_SEARCH_NAME = 'name';
    const COUNTRY_SEARCH_COUNTRY_CODE = 'country_code';
    const COUNTRY_SEARCH_TYPE = 'type';

    const TARGETING_SEARCH_PLATFORM = 'platform';

    const FIELD_FREQUENCY_DISTRIBUTION = 'frequency_distribution';

    const FIELD_CURVE_REACH = 'reach';
    const FIELD_CURVE_BUDGET = 'budget';
    const FIELD_CURVE_IMPRESSION = 'impression';
    const FIELD_CURVE_CLICK = 'click';
    const FIELD_CURVE_CONVERSION = 'conversion';
    const FIELD_CURVE_NUM_POINTS = 'num_points';

    const POINT_MIN_INDEX = 'min_index';
    const POINT_MAX_INDEX = 'max_index';

    const DELIVERY_ESTIMATE_PARAM_TARGETING = 'targeting_spec';
    const DELIVERY_ESTIMATE_PARAM_OPTIMIZATION = 'optimization_goal';
}