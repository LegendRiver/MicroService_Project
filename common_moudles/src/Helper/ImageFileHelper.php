<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2017/12/21
 * Time: 下午4:02
 */

namespace CommonMoudle\Helper;

use CommonMoudle\Constant\LogConstant;
use CommonMoudle\Logger\ServerLogger;
use CommonMoudle\Constant\ImageInfoConstant;


class ImageFileHelper
{
    public static function readLocalImage($localImagePath)
    {
        $imageData = file_get_contents($localImagePath);

        return self::buildImageInfo($imageData, $localImagePath);
    }

    public static function downloadImage($url, $localPath, $fileName = '', $downLoadType = 1)
    {
        $localFileStream = null;
        try
        {
            if($url == '')
            {
                return false;
            }

            $ext = strrchr($url,'.');
            if($fileName == '')
            {
                if($ext!=".gif" && $ext!=".jpg" && $ext!=".png" && $ext!=".jpeg")
                {
                    ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The file extend is ' . $ext);
                    return false;
                }
                $fileName = time().'_'.basename($url);
            }
            else
            {
                $fileName .= $ext;
            }

            $saveFilePath = $localPath . DIRECTORY_SEPARATOR . $fileName;
            if(file_exists($saveFilePath))
            {
                ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'The file have existed : ' . $saveFilePath);
                return false;
            }

            if(0 === $downLoadType)
            {
                $img = self::getImageByOb($url);
            }
            else
            {
                $img = self::getImageByCurl($url);
            }

            if(false === $img)
            {
                return false;
            }

            $localFileStream = @fopen($saveFilePath, 'a');
            fwrite($localFileStream, $img);
            fclose($localFileStream);

            return self::buildImageInfo($img, $saveFilePath, $url);
        }
        catch (\Exception $e)
        {
            if(!is_null($localFileStream))
            {
                fclose($localFileStream);
            }

            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to download image: ' . $url);
        }
        return array();
    }

    private static function buildImageInfo($imageData, $saveFilePath, $url = '')
    {
        $imageSize = strlen($imageData);
        $imageInfo = getimagesizefromstring($imageData);
        $imageEntity = array();
        if(empty($url))
        {
            $imageEntity[ImageInfoConstant::IMAGE_URL] = $saveFilePath;
        }
        else
        {
            $imageEntity[ImageInfoConstant::IMAGE_URL] = $url;
        }

        $imageEntity[ImageInfoConstant::IMAGE_SAVE_PATH] = $saveFilePath;
        $imageEntity[ImageInfoConstant::IMAGE_SIZE] = $imageSize;
        $imageEntity[ImageInfoConstant::IMAGE_WIDTH] = $imageInfo[0];
        $imageEntity[ImageInfoConstant::IMAGE_HEIGHT] = $imageInfo[1];
        $imageEntity[ImageInfoConstant::IMAGE_MIME_TYPE] = $imageInfo['mime'];

        return $imageEntity;
    }

    private static function getImageByOb($url)
    {
        //开启缓存
        ob_start();
        $imageSize = readfile($url);
        if(false === $imageSize)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to read image file by url : ' . $url);
            return false;
        }
        $img = ob_get_contents();
        ob_end_clean();

        return $img;
    }

    private static function getImageByCurl($url)
    {
        $ch=curl_init();
        if(false === $ch)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to initialize curl.');
            return false;
        }

        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);

        $img=curl_exec($ch);
        if(false === $img)
        {
            ServerLogger::instance()->writeLog(LogConstant::LOGGER_LEVEL_WARNING, 'Failed to read image file by url : ' . $url);
            return false;
        }
        curl_close($ch);

        return $img;
    }
}