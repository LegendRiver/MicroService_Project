<?php
/**
 * Created by IntelliJ IDEA.
 * User: Feng
 * Date: 2018/1/17
 * Time: 下午3:44
 */

namespace CommonMoudle\Http;


class RequestParameter extends \ArrayObject
{
    public function enhance(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this[$key] = $value;
        }
    }

    protected function exportNonScalar($value)
    {
        return json_encode($value);
    }

    public function export()
    {
        $data = array();
        foreach ($this as $key => $value)
        {
            $data[$key] = is_null($value) || is_scalar($value)
                ? $value
                : $this->exportNonScalar($value);
        }

        return $data;
    }
}