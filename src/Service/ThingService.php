<?php


namespace App\Service;

use Doctrine\Common\Collections\Collection;

class ThingService
{

    public static function decamelise($input, $separator = '_')
    {
        return $output = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), $separator);
    }

    public static function camelise($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    public static function generateUuid($prefix)
    {
        return uniqid(strtoupper(ThingService::decamelise($prefix)).'_', false);
    }

    public static function decodeDateTime($date): \DateTime
    {
        $value = null;
        if (is_string($date)) {
            return new \DateTime($date);
        }
        if (is_array($date)) {
            if (array_key_exists($date, 'timezone') && array_key_exists($date, 'timezone_type') && array_key_exists($date, 'date')) {
                if ($date['timezone_type'] == 3) {
                    $value = new \DateTime($date['date'], new \DateTimeZone($date['timezone']));
                } else {
                    $value = new \DateTime($date['date']);
                }
            }
        }
        if (is_object($date)) {
            if (property_exists($date, 'timezone') && property_exists($date, 'timezone_type') && property_exists($date, 'date')) {
                if ($date->timezone_type == 3) {
                    $value = new \DateTime($date->date, new \DateTimeZone($date->timezone));
                } else {
                    $value = new \DateTime($date->date);
                }
            }
        }
        return $value;
    }


    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    public static function cast($sourceObject, $destination)
    {
        if (is_array($sourceObject)) {
            $sourceObject = json_decode(json_encode($sourceObject));
        }

        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);

            // Transform Source Value before Copying
            if (is_object($value)) {
                if (property_exists($value, 'timezone') && property_exists($value, 'timezone_type') && property_exists($value, 'date')) {
                    if ($value->timezone_type == 3) {
                        $value = new \DateTime($value->date, new \DateTimeZone($value->timezone));
                    } else {
                        $value = new \DateTime($value->date);
                    }
                }
            }
            // End Transformation

            if ($name === 'uuid' && $value === null) {
                continue;
            }

            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $getter = 'get'.ucfirst($name);
                $getterIs = 'is'.ucfirst($name);
                $propDestValue = null;
                if (!$propDest->isPublic()) {
                    if ($destinationReflection->hasMethod($getter)) {
                        try {
                            $propDestValue = $destination->{$getter}();
                        } catch (\TypeError $exception) {

                        }
                    } elseif ($destinationReflection->hasMethod($getterIs)) {
                        try {
                            $propDestValue = $destination->{$getterIs}();
                        } catch (\TypeError $exception) {

                        }
                    }
                } else {
                    $propDestValue = $propDest->getValue($destination);
                }

                if ($propDestValue instanceof Collection) {
                    if (is_array($value)) {
                        foreach ($value as $itemValue) {
                            $propDestValue->add($itemValue);
                        }
                        continue;
                    }
                }

                $setter = 'set'.ucfirst($name);
                if ($destinationReflection->hasMethod($setter)) {
                    $destination->{$setter}($value);
                } else {
                    $propDest->setAccessible(true);
                    $propDest->setValue($destination, $value);
                }
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    public static function generate4DigitCode($code = null)
    {
        if (empty($code)) {
            $code = base_convert(rand(0, 1679615), 10, 36);
        }

        if (strlen($code) > 4) {
            return strtoupper(substr($code, -4, 4));
        }

        for ($i = 0; $i < 4 - strlen($code);) {
            $code = '0'.$code;
        }

        return strtoupper($code);
    }

}