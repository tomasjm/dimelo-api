<?php

class ProfileController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    function getUserInfo($user) {
        $usuario = Users::findFirstByUser($user);

        if($usuario) {
            $usuario->email = null;
            $usuario->password = null;
            return array('response' => true, 'user' => $usuario);
        } else {
            return array('response' => false);
        }
    }
    function getUserInfo2() {
        return array('alv'=>true);
    }

}

