<?php
namespace Harmony\Resources;


class Environment
{
    public function get($var)
    {
        if(!empty($_ENV[$var]))
            return $_ENV[$var];

        return null;
    }

    public function getByPrefix($var_prefix) : array
    {
        $prefix_arr = [];
        foreach(array_keys($_ENV) as $key)
        {
            if(preg_match("/$var_prefix/i", $key))
            {
                $prefix_arr[$key] = $_ENV[$key];
            }
        }

        return $prefix_arr;
    }
}