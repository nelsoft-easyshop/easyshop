<?php

namespace EasyShop\Activity;

class ActivityTypeInformationUpdate implements ActivityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function constructJSON($changeSet)
    {
        $fields = [];
        foreach($changeSet as $fieldName => $fieldValue){
            $field = new ActivityField();
            $field->setNewValue($fieldValue);
            $field->setFieldName($fieldName);
            $fields[] = $field->toArray();
        }

        return json_encode($fields);
    }
}


