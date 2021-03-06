<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/4
 * Time: 上午11:52
 */

namespace FBBasicService\Common;


use CommonMoudle\Service\ServiceResult;
use FBBasicService\Constant\FBServiceStatusCode;

class FBServiceResult extends ServiceResult
{
    protected function getSubMessage()
    {
        return array(
            FBServiceStatusCode::GET_FB_ACCOUNT_FAILED => 'Failed to get account by fb api',
            FBServiceStatusCode::QUERY_PARAM_INCOMPLETE => 'Incomplete request parameters',

            FBServiceStatusCode::QUERY_INSIGHT_EMPTY => 'The insight of account is empty',
            FBServiceStatusCode::FAILED_AD_TYPE => 'Failed to get ad type from facebook api',
            FBServiceStatusCode::EMPTY_AD_BY_ADSET => 'The ads is empty from adset',
            FBServiceStatusCode::FAILED_AD_BY_ADSET => 'Failed to get ad by adsetId',
            FBServiceStatusCode::FAILED_ADSET_FIELDS => 'Failed to get all fields of adset',
            FBServiceStatusCode::FAILED_QUERY_AD => 'Failed to query ad by adId',
            FBServiceStatusCode::FAILED_CREATIVE_FIELDS => 'Failed to get all fields of creative',

            FBServiceStatusCode::EMPTY_REQUEST_PARAM => 'The params of request have empty value',
            FBServiceStatusCode::FAILED_CREATE_ADSET => 'Failed to create adset by fields',
            FBServiceStatusCode::FAILED_COPY_ADSET => 'Failed to copy adset by copy api of fb',
            FBServiceStatusCode::FAILED_UPDATE_ADSET_NAME => 'Failed to update adset name',

            FBServiceStatusCode::FAILED_QUERY_VIDEO => 'Failed to query video by id',
            FBServiceStatusCode::FAILED_CREATE_CREATIVE => 'Failed to create creative',
            FBServiceStatusCode::FAILED_CREATE_AD => 'Failed to create ad',
            FBServiceStatusCode::INVALID_CREATIVE => 'The creative is invalid',
        );
    }
}