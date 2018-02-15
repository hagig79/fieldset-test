<?php

class Controller_Test extends Controller
{
    function action_index()
    {
        $form = Fieldset::forge();
        $myfield = new Myfield('elem');
        $myfield->setText("Amazonランキング {field} 以下");
        $form->add($myfield);
        $form->add('submit', '', ['type' => 'submit', 'value' => '登録']);
        if (\Input::method() == 'POST') {
            $form->repopulate();
        }
        $view = View::forge('test/index');
        $view->set_safe('form', $form->build());
        return $view;
    }
}
