<?php

class Controller_Test extends Controller
{
    function action_index()
    {
        $form = MyFieldset::forge();
        $myfield = new Myfield('elem1');
        $myfield->setTemplate("<div>{checkbox}Amazonランキング {field1} 以下 {field2} 以上</div>");
        $myfield->add_field_rule(1,'valid_string', 'numeric')->set_label('項目１');
        $form->add($myfield);
        $myfield2 = new Myfield('elem2');
        $myfield2->setTemplate("<div>{checkbox}Amazonランキング {field1} 以下 {field2} 以上 {field3}</div>");
        $myfield2->add_field_rule(1,'valid_string', 'numeric')->set_label('項目２');
        $form->add($myfield2);
        $form->add('submit', '', ['type' => 'submit', 'value' => '登録']);
        $error = '';
        if (\Input::method() == 'POST') {

            var_dump($form->validation()->run());
            if (!$form->validation()->run()) {
                $form->validation()->set_message('valid_string', ':valueは不正な値です。『:label』は正しい数字を入力してください。');
                $error = $form->validation()->show_errors();
            }

            $form->repopulate();
        }
        $view = View::forge('test/index');
        $view->set_safe('form', $form->build());
        $view->set_safe('error', $error);
        return $view;
    }
}
