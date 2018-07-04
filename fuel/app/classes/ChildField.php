<?php

class ChildField extends \Fuel\Core\Fieldset_Field
{
    protected $parent_fieldset;

//    function __construct($name, $label = '', array $attributes = array(), array $rules = array(), Fieldset $fieldset = null)
//    {
//        parent::__construct($name, $label, $attributes, $rules, null);
//        $this->parent_fieldset = ;
//    }

    function fieldset()
    {
        return $this->parent_fieldset;
    }

    function set_fieldset(\Fuel\Core\Fieldset $fieldset)
    {
        $this->parent_fieldset = $fieldset;
    }

}