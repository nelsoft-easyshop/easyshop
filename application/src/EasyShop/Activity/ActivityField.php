<?php

namespace EasyShop\Activity;

class ActivityField
{
    /**
     * Current Value
     * @var string
     */
    private $newValue;

    /**
     * Name of Field
     * @var string
     */
    private $fieldName;
    
    /**
     * Set the current value
     * 
     * @param string $previousValue
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    }
    
    /**
     * Get the new value
     * 
     * @return string
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set the field name
     * 
     * @param string $previousValue
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }
    
    /**
     * Get the field name
     * 
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    
    /**
     * Obtain simple associative array of the field object
     *
     * @return mixed
     */
    public function toArray()
    {
        $arrayThis = [
            'fieldname' => $this->fieldName,
            'fieldValue' => $this->newValue,
        ];
        return $arrayThis;
    }

}


