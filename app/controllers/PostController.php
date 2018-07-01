<?php

class PostController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }
    function getPosts($user_id) {
        // RUTA CON PARAMETROS
        $posts = Posts::findByReceiver($user_id);
        $newPosts = array();
        foreach ($posts as $post) {
            if ($post->from) {
                if ($post->anonymous != 1) {
                    $user = Users::findFirstById($post->from);
                    $post->from = array('name' => $user->name, 'url'=>$user->user, 'photo'=>$user->photo, 'role'=>$user->role);
                } else {
                    $post->from = array('name' => 'Anonimo');
                }
            } else {
                $post->from = array('name' => 'Anonimo');
            }
            $newPosts[] = $post;
        }
        return $newPosts;
        
        

    }
    function createPost($app) {
        /*
        {
            "text": text,
            "from": from,
            "receiver": receiver,
            "anonymous": bool
        }
        */
        $datos = json_decode($app->request->getRawBody());
        $newPost = new Posts();
        $newPost->text = $datos->text;
        $newPost->from = $datos->from;
        $newPost->receiver = $datos->receiver;
        if ($datos->anonymous) {
            $newPost->anonymous = 1;
        } else {
            $newPost->anonymous = 0;
        }
        $newPost->created_at = time();
        $newPost->create();
        return array('response' => true, 'post' => $newPost);
    }

    public function deletePost($app) {
        $datos = json_decode($app->request->getRawBody());
        $post = Posts::findFirstById($datos->post_id);
        if ($post) {
            if ($post->receiver == $datos->user_id) {
                $post->delete();
                return array('response' => true);
            } else {
                return array('response' => false);
            }
        }
        return array('response' => false);
    }

}

