<?php
/**
 * Created by PhpStorm.
 * User: hagio
 * Date: 2018/07/04
 * Time: 22:01
 */

class MyValidation extends \Fuel\Core\Validation
{

    public function run($input = null, $allow_partial = false, $temp_callables = array())
    {
        if (is_null($input) and \Input::method() != 'POST')
        {
            return false;
        }

        // Backup current state of callables so they can be restored after adding temp callables
        $callable_backup = $this->callables;

        // Add temporary callables, reversed so first ends on top
        foreach (array_reverse($temp_callables) as $temp_callable)
        {
            $this->add_callable($temp_callable);
        }

        static::set_active($this);

        $this->validated = array();
        $this->errors = array();
        $this->input = $input ?: array();
        $fields = $this->field(null, true);
        foreach($fields as $field)
        {
            static::set_active_field($field);

            // convert form field array's to Fuel dotted notation
            $name = str_replace(array('[', ']'), array('.', ''), $field->name);

            $value = $this->input($name);
            if (($allow_partial === true and $value === null)
                or (is_array($allow_partial) and ! in_array($field->name, $allow_partial)))
            {
                continue;
            }
            if ($field instanceof Myfield) {
                foreach ($field->children() as $f) {
                    $value = $this->input($f->name);
                    try
                    {
                        foreach ($f->rules as $rule)
                        {
                            var_dump($rule[0]);
                            var_dump($f->name);
                            var_dump($value);
                            $callback  = $rule[0];
                            $params    = $rule[1];
                            $this->_run_rule($callback, $value, $params, $f);
                        }
                        if (strpos($name, '.') !== false)
                        {
                            \Arr::set($this->validated, $name, $value);
                        }
                        else
                        {
                            $this->validated[$name] = $value;
                        }
                    }
                    catch (Validation_Error $v)
                    {
                        $this->errors[$f->name] = $v;

                        if($field->fieldset())
                        {
                            $field->fieldset()->Validation()->add_error($f->name, $v);
                        }
                    }
                }
            } else {
                try
                {
                    foreach ($field->rules as $rule)
                    {
                        var_dump($rule[0]);
                        var_dump($name);
                        var_dump($value);
                        $callback  = $rule[0];
                        $params    = $rule[1];
                        $this->_run_rule($callback, $value, $params, $field);
                    }
                    if (strpos($name, '.') !== false)
                    {
                        \Arr::set($this->validated, $name, $value);
                    }
                    else
                    {
                        $this->validated[$name] = $value;
                    }
                }
                catch (Validation_Error $v)
                {
                    $this->errors[$field->name] = $v;

                    if($field->fieldset())
                    {
                        $field->fieldset()->Validation()->add_error($field->name, $v);
                    }
                }
            }
        }

        static::set_active();
        static::set_active_field();

        // Restore callables
        $this->callables = $callable_backup;

        return empty($this->errors);
    }
}