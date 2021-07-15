<?php

namespace Personmanager\PersonManager\Utility;

class EmailHashUtility
{
    /**
     * Generate a random string of given length
     * 
     * @param string $email the requested length
     * @return string
     */
    public static function generateHash($email): string
    {
        return md5($email);
    }
}