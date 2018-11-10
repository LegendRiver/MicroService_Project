<?php
namespace OrionService\Common;

use CommonMoudle\Service\ServiceResult;
use OrionService\Constant\OrionServiceStateCode;

class OrionServiceResult extends ServiceResult
{
    protected function getSubMessage()
    {
        return array(
            OrionServiceStateCode::QUERY_PRODUCT_FAILED => 'Failed to query product by db service.',
            OrionServiceStateCode::PARAM_PRODUCT_ID_EMPTY => 'The param productId is empty',
            OrionServiceStateCode::QUERY_ACCOUNT_SERVICE_FAILED => 'Failed to query accountInfo by productId',
            OrionServiceStateCode::QUERY_ACCOUNT_GEO_FAILED => 'Failed to query account geo insight by productId. The result of query accountList is false',

            OrionServiceStateCode::PARAM_START_DATE_EMPTY => 'The param startDate is empty',
            OrionServiceStateCode::PARAM_END_DATE_EMPTY => 'The param endDate is empty',

            OrionServiceStateCode::UPLOAD_PARAM_FILE_DATA => 'The query param fileContent is empty',
            OrionServiceStateCode::UPLOAD_PARAM_USER_NAME => 'The query param userName is empty',
            OrionServiceStateCode::UPLOAD_PARAM_USER_ID => 'The query param userId is empty',
            OrionServiceStateCode::UPLOAD_PARAM_TASK_NAME => 'The query param taskName is empty',
            OrionServiceStateCode::UPLOAD_SAVE_FILE_FAIL => 'Failed to save file from form data',
            OrionServiceStateCode::UPLOAD_UNZIP_FAIL => 'Failed to unzip file',

            OrionServiceStateCode::QUERY_USER_BY_NAME_FAILED => 'Failed to query user by db service using parameter userName.',
            OrionServiceStateCode::QUERY_USER_BY_NAME_PARAMETER_LOSS => 'Failed to query user by db service as one of the parameter lossed or both.',
            OrionServiceStateCode::QUERY_USER_BY_NAME_PARAMETER_WRONG => 'UserName or password wrong.',
            OrionServiceStateCode::QUERY_USER_FAILED_GEN_TOKEN => 'Failed to generate token.',

			OrionServiceStateCode::UPLOAD_UNZIP_FAIL => 'Orion_service UploadMaterialService unzipDir false',
			OrionServiceStateCode::GET_JSON_NAME_FAILED => 'Orion_service UploadMaterialService MaterialValidHandler:  getJsonName jsonName empty.',
			OrionServiceStateCode::INSERT_DUPLICATE_TASK_FAILED => 'Orion_service UploadMaterialService duplicateTaskInsert insertResult false.',

			OrionServiceStateCode::DUPLICATE_TASK_USER_ID_EMPTY => 'Maybe the parameter userId is empty, please check the param.',
			OrionServiceStateCode::QUERY_TASK_BY_USER_ID_FAILED => 'Failed to query task by db service using parameter userId.',

			OrionServiceStateCode::GET_FILE_LIST_FAIL => 'Get fileList fail from the root path.',
			OrionServiceStateCode::JSON_COUNT_NOT_EQUALS_ONE => 'Json file is not equals one in the root path.',
			OrionServiceStateCode::GET_JSON_PATH_FAIL => 'Get jsonPath fail from the root path.',
			OrionServiceStateCode::GET_JSON_DATA_FAIL => 'Get jsonData fail from the root path.',
			OrionServiceStateCode::EXCEL_TYPE_VALID_FAIL => 'The file type is not excel in the root path.',
			OrionServiceStateCode::FROM_ADSET_ID_EMPTY => 'The key fromAdsetId in json file is not allowed to be null.',
			OrionServiceStateCode::GET_CONFIGINFOS_FAIL => 'Get configinfos fail from the root path.',
			OrionServiceStateCode::GET_JUDGEFROMADSETID_FAIL => 'Get judgeFromAdsetId not equals 200 from the root path.',
			OrionServiceStateCode::GET_JUDGEACCOUNTIDCAMPAIGNID_FAIL => 'Get judgeAccountIdCampaignId not equals 200 from the root path.',
			OrionServiceStateCode::ISSHAREMATERIAL_MATERIALPATH_VALID_ERROR => 'The key materialPath in json file is not allowed to be null while the key isShareMaterial values false.',
			OrionServiceStateCode::NO_DIRECTORY_EXIST_ERROR => 'The dirList in the directory is null, maybe there is not any materials.',
			OrionServiceStateCode::CARROUSEL_TYPE_VALID_ERROR => 'The carousel types could not contain single file in given path.',
			OrionServiceStateCode::NO_AD_FILES_EXIST => 'There is no advertisement exist.',
        );
    }
}