<?php
namespace CommonMoudle\Service;
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/2
 * Time: 上午10:40
 */
class ServiceBase
{
    public function callFunction($functionName, $parameters)
    {
        if (method_exists($this, $functionName))
        {
            return call_user_func_array(array($this, $functionName), array($parameters));
        }
    }
}