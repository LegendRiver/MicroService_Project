<?php
namespace FBBasicService\Common;

use CommonMoudle\Logger\ServerLogger;
use FacebookAds\Http\Exception\RequestException;

class FBLogger extends ServerLogger
{
    protected function appendOtherExceptionMessage(\Exception $e)
    {
        $message = '';
        if($e instanceof RequestException)
        {
            $message .= ' ; ';
            $message .= $e->getErrorSubcode();
            $message .= ' ; ';
            $message .= $e->getErrorUserTitle();
            $message .= ' ; ';
            $message .= $e->getErrorUserMessage();
        }

        return $message;
    }
}