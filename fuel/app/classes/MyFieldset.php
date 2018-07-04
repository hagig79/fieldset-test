<?php

class MyFieldset extends \Fuel\Core\Fieldset
{
    public function __construct($name = '', array $config = array())
    {
        parent::__construct($name, $config);
    }

    public function validation($instance = true)
    {
        if ($instance instanceof Validation) {
            $this->validation = $instance;
            return $instance;
        }

        if (empty($this->validation) and $instance === true) {
            $this->validation = MyValidation::forge($this);
        }

        return $this->validation;
    }

//    public function field($name = null, $flatten = false, $tabular_form = true)
//    {
//        if (!$name) {
//            $fields = [];
//            foreach ($this->fields as $name => $field) {
//                if ($field instanceof Myfield) {
//                    $fields += $field->fields();
//                } else {
//                    $fields[$name] = $field;
//                }
//            }
//            return $fields;
//        } else {
//            return parent::field($name, $flatten, $tabular_form);
//        }
//    }
}