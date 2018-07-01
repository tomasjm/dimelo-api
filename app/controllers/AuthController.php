<?php

class AuthController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }
    public function login($app) {
        /* JSON POST
        {
            "email" : email,
            "password": password
        }
        
        */
        $datos = json_decode($app->request->getRawBody());
        $usuario = Users::findFirstByEmail($datos->email);
        if ($usuario) {
            if ($usuario->password == md5($datos->password)) {
                $usuario->auth_key = md5($usuario->id . time());
                $usuario->update();
                return array('response'=> true, 'user' => $usuario);
            } else {
                return array('response' => false, 'message' => "Datos ingresados incorrectos");
            }
        } else {
            return array('response' => false, 'message' => "Datos ingresados incorrectos");
        }

    }

    public function register($app) {
        /* JSON POST
        {
            "name": name,
            "email": email,
            "password": password,
            "user": user

        }
        
        */
        $datos = json_decode($app->request->getRawBody());
        $email = Users::findFirstByEmail($datos->email);
        if ($email) {
            return array('response' => false, 'message' => 'Este correo ya se encuentra registrado!');
        }
        $user = Users::findFirstByUser($datos->user);
        if ($user) {
            return array('response' => false, 'message' => 'Este usuario ya se encuentra registrado!');
        }
        $regUsuario = new Users();
        $regUsuario->name = $datos->name;
        $regUsuario->email = $datos->email;
        $regUsuario->password = md5($datos->password);
        $regUsuario->user = $datos->user;
        $regUsuario->role = "USER_ROLE";
        $regUsuario->desc = 'Hola! Soy nuevo en Dimelo.pw!';
        $regUsuario->photo = 'avatar.png';
        $regUsuario->postperm = 1;
        $regUsuario->banned = 0;
        $regUsuario->create();
        $regUsuario->auth_key = md5($regUsuario->id . time());
        $regUsuario->update();
        return array('response' => true, 'user' => $regUsuario);
    }

    public function checksession($app) {
        /* JSON POST
        {
            "user_id": user_id logueado,
            "auth_key": auth_key almacenado,
        }
        */
        $datos = json_decode($app->request->getRawBody());
        $usuario = Users::findFirstByAuth_key($datos->auth_key);
        if ($usuario) {
            if ($usuario->id == $datos->user_id) {
                return array('response'=> true, 'user' => $usuario);
            } else {
                return array('response'=> false, 'message' => 'Debes volver a iniciar sesión');
            }
        } else {
            return array('response'=> false, 'message' => 'Debes volver a iniciar sesión');
        }

    }

}

