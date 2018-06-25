<?php

class PostController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }
    function getPosts($user_id) {
        $posts = Posts::findByReceiver($user_id);
        $newPosts = array();
        foreach ($posts as $post) {
            if ($post->from) {
                if ($post->anonymous != 1) {
                    $post->from = Users::findFirstById($post->from)->name;
                } else {
                    $post->from = "Anonimo";
                }
            } else {
                $post->from = "Anonimo";
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

}

