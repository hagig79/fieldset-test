<?php

class Myfield extends \Fuel\Core\Fieldset_Field
{
    protected $template;
    protected $checked;
    protected $value1;
    protected $value2;
    protected $values;
    protected $fields;

    function __construct($name, $label = '', array $attributes = array(), array $rules = array(), Fieldset $fieldset = null)
    {
        parent::__construct($name, $label, $attributes, $rules, $fieldset);
        $this->fields = [];
//        $this->fields[$name . '_1'] = new ChildField($name . '_1', $label, $attributes, $rules, $fieldset);
//        $this->fields[$name . '_1']->add_rule('valid_string', 'numeric');
//        $this->fields[$name . '_1']->set_label('入力項目1');
//        $this->fields[$name . '_2'] = new ChildField($name . '_2', $label, $attributes, $rules, $fieldset);
//        $this->fields[$name . '_2']->add_rule('valid_string', 'numeric');
//        $this->fields[$name . '_2']->set_label('入力項目2');
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        $this->fields = [];
        for ($i = 1; $i < 5; $i++) {
            if (strpos($template, "{field{$i}}") !== false) {
                $this->fields[] = new ChildField($this->name . '_' . $i);
            }
        }
    }

    public function build()
    {
        $text = $this->template;
        for ($i = 0; $i < count($this->fields); $i++) {
            $name = $this->name . '_' . ($i + 1);
            $value = isset($this->values[$i]) ? $this->values[$i] : null;
            $text = str_replace('{field' . ($i + 1) . '}', "<input type='text' name=\"{$name}\" value=\"{$value}\">", $text);
        }
        $text = str_replace('{checkbox}', "<input type=\"checkbox\" name=\"{$this->name}_check\" value=\"1\" " . ($this->checked ? 'checked' : '') . ">", $text);
        return $text;
    }

    public function input()
    {
        $values = [];
        $values[] = \Input::post($this->name . "_check");
        for ($i = 0; $i < count($this->fields); $i++) {
            $values[] = \Input::post($this->name . "_" . ($i + 1));
        }
        return $values;
    }

    public function set_value($value, $repopulate = false)
    {
        if ($value[0]) {
            $this->checked = 1;
        } else {
            $this->checked = 0;
        }
        $this->value = $value[1];
        $this->values = array_slice($value, 1);
    }

    public function set_field_label($index, $label)
    {
        return $this->fields[$index - 1]->set_label($label);
    }

    public function add_field_rule($index, $callback, ...$args)
    {
        return $this->fields[$index - 1]->add_rule($callback, $args);
    }

    public function fields()
    {
        return $this->fields;
    }

    public function set_fieldset(\Fuel\Core\Fieldset $fieldset)
    {
        foreach ($this->fields as $field) {
            $field->set_fieldset($fieldset);
        }
        return parent::set_fieldset($fieldset);
    }

    public function children()
    {
        $children = [];
        return $this->fields();
    }

}