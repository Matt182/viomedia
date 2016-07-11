<?php
namespace task2;

class Utils
{
    /** @param array $array @param DB $db @return string */
    static public function serialize($array, $db)
    {
        return $db->sanitize(serialize($array));
    }

    /** @param string $string @return array */
    static public function unserialize($string)
    {
        return unserialize($string);
    }
}
