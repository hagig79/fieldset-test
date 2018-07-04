<?php

class Controller_Test extends Controller
{
    function action_index()
    {
        $form = MyFieldset::forge();
        $myfield = new Myfield('elem');
        $myfield->setTemplate("{checkbox}Amazonランキング {field1} 以下 {field2} 以上");
        $myfield->add_rule('valid_string', 'numeric');
        $form->add($myfield);
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
