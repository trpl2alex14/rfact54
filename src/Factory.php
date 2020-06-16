<?php

namespace RFAct54;

use Exception;
use RFAct54\Exception\RuntimeException;

class Factory
{

    /**
     *
     * @param string $class
     * @param OrderInterface
     * @throws RuntimeException
     * @return OrderBundle
     *
     **/
    public static function create($class, OrderInterface $order)
    {
        $class = self::getClassName($class);

        try {
            $orderBundle = new $class($order);
        }catch (Exception $e){
            throw new RuntimeException("Class '$class' not found");
        }

        return $orderBundle;
    }


    private static function getClassName($shortName)
    {
        if (0 === strpos($shortName, '\\')) {
            return $shortName;
        }

        // replace underscores with namespace marker, PSR-0 style
        $shortName = str_replace('_', '\\', $shortName);
        if (false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }

        return '\\RFAct54\\'.$shortName.'OrderBundle';
    }

}