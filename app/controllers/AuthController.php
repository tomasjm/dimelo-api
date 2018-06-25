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
            TODO: generar auth key
        }
        
        */
        $datos = json_decode($app->request->getRawBody());
        $usuario = Users::findFirstByEmail($datos->email);
        if ($usuario) {
            if ($usuario->password == md5($datos->password)) {
                return array('response'=> true, 'user' => $usuario);
            } else {
                return array('response' => false, 'msg' => "Datos ingresados incorrectos");
            }
        } else {
            return array('response' => false, 'msg' => "No existe este correo en nuestra base de datos");
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
            return array('response' => false, 'msg' => 'Este correo ya se encuentra registrado!');
        }
        $user = Users::findFirstByUser($datos->user);
        if ($user) {
            return array('response' => false, 'msg' => 'Este usuario ya se encuentra registrado!');
        }
        $regUsuario = new Users();
        $regUsuario->name = $datos->name;
        $regUsuario->email = $datos->email;
        $regUsuario->password = md5($datos->password);
        $regUsuario->user = $datos->user;
        $regUsuario->role = "USER_ROLE";
        $regUsuario->description = null;
        $regUsuario->photo = null;
        $regUsuario->create();
        return array('response' => true, 'user' => $regUsuario);

    }

}

