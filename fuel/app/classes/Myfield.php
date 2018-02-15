<?php

class Myfield extends \Fuel\Core\Fieldset_Field
{
    protected $text;
    protected $checked;

    function __construct($name, $label = '', array $attributes = array(), array $rules = array(), Fieldset $fieldset = null)
    {
        parent::__construct($name, $label, $attributes, $rules, $fieldset);
    }

    function setText($text)
    {
        $this->text = $text;
    }

    public function build()
    {
        $checkbox = "<input type=\"checkbox\" name=\"{$this->name}_check\" value=\"1\" " . ($this->checked ? 'checked' : '') . ">";
        return $checkbox .str_replace('{field}', "<input type='text' name=\"{$this->name}\" value=\"{$this->value}\">", $this->text);;
    }

    public function input()
    {
        return [\Input::post($this->name."_check"), \Input::post($this->name)];
    }

    public function set_value($value, $repopulate = false)
    {
        if ($value[0]) {
            $this->checked = 1;
        } else {
            $this->checked = 0;
        }
        $this->value = $value[1];
    }
}