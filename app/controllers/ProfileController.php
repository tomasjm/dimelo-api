<?php

class ProfileController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    function getUserInfo($user) {
        //RUTA CON PARAMETROS
        $usuario = Users::findFirstByUser($user);

        if($usuario) {
            $usuario->email = null;
            $usuario->password = null;
            return array('response' => true, 'user' => $usuario);
        } else {
            return array('response' => false);
        }
    }

    public function updateUser($app) {
        /* JSON POST
        {
            "user_id": user_id logueado,
            "name": nuevo nombre,
            "desc": nueva descripción,
            "user": nueva url del perfil,
        }
        */
        $datos = json_decode($app->request->getRawBody());
        $URL = Users::findFirstByUser($datos->user);
        if ($URL) {
            if ($URL->id != $datos->user_id) {
                return array('response' => false, 'message' => 'Ya está ocupada esta URL');
            }
        }
        $user = Users::findFirstById($datos->user_id);
        if ($user) {
            $user->name = $datos->name;
            $user->desc = $datos->desc;
            $user->user = $datos->user;
            if ($datos->postperm) {
                $user->postperm = 1;
            } else {
                $user->postperm = 0;
            }
            $user->update();
            return array('response'=>true, 'user' => $user);
        }
        return array('response'=>false);
    }

    public function updateUserPhoto($app) {
        // formDATA! user_id // photo
        $user = Users::findFirstById($this->request->get('user_id'));
        if ($user) {
            $extension;
            if ($this->request->hasFiles()) {
                //DEVUELVE SI O SI UN ARRAY
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $arrayextension = explode('.', $file->getName());
                    $extension = $arrayextension[sizeof($arrayextension) - 1];
                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                        $time = time();
                        $file->moveTo('files/' . $user->id . $time . '.' . $extension);
                        $user->photo = $user->id . $time . '.' . $extension;
                        $user->update();
                        return array('response' => true, 'user' => $user);
                    } else {
                        $resultado = array('response' => false, 'message' => 'Formato de la foto no valido');
                    }
                }
            }
        }  
    }
}



