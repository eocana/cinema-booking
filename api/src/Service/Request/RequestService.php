<?php

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestService
{
    /**
     * return mixed
     */
    public static function getField(Request $request, string $fieldName, bool $isRequired = true, bool $isArray = false)
    {
        $requestData = json_decode($request->getContent(), true);
        
        if($isArray){
            $arrayData = self::arrayFlatten($requestData);

            foreach($arrayData as $key => $value){
                if($key === $fieldName){
                    return $value;
                }
            }

            if($isRequired){
                throw new BadRequestHttpException(sprintf('The field %s is required', $fieldName));
            }

            return null;
        }

        if(isset($requestData[$fieldName])){

            return $requestData[$fieldName];
        }

        if($isRequired){
                throw new BadRequestHttpException(\sprintf('The field %s is required', $fieldName));
        }

        return null;
    }

    /**
     * "Aplanar" un array multidimensional en un array simple de una dimensión.
     */
    public static function arrayFlatten(array $array): array
    {   
        $return = [];

        foreach($array as $key => $value){
            if(is_array($value)){
                $return = array_merge($return, self::arrayFlatten($value));
            } else{
                $return[$key] = $value;
            }
        }

        return $return;
    }
}