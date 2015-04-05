<?php

namespace EasyShop\Activity;

class ActivityTypeInformationUpdate
{
    /**
     * Build JSON String contract
     *
     * @param mixed $changeSet
     * @return string
     */
    public function constructJSON($changeSet)
    {
        $fields = [];
        foreach($changeSet as $fieldName => $fieldValue){
            $field = [
                'fieldName' => $fieldName,
                'fieldValue' => $fieldValue,
            ];
            $fields[] = $field;
        }

        return json_encode($fields);
    }
}


