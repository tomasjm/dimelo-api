<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
// CORS

$app->before(function () use ($app) {
    $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';
    $app->response->setHeader("Access-Control-Allow-Origin", $origin)
        ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
        ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization, Accept')
        ->setHeader("Access-Control-Allow-Credentials", true);
    $app->response->sendHeaders();
    return true;
});
/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});


$app->post('/auth/login', function() use($app) {
    echo json_encode ( (new AuthController)->login($app));
});
$app->post('/auth/register', function() use($app) {
    echo json_encode ( (new AuthController)->register($app));
});
$app->post('/auth/check', function() use($app) {
    echo json_encode ( (new AuthController)->checksession($app));
});

$app->post('/profile/update', function() use($app) {
    echo json_encode ( (new ProfileController)->updateUser($app));
});
$app->post('/profile/updatephoto', function() use($app) {
    echo json_encode ( (new ProfileController)->updateUserPhoto($app));
});


$app->post('/post/create', function() use($app) {
    echo json_encode ( (new PostController)->createPost($app));
});
$app->post('/post/delete', function() use($app) {
    echo json_encode ( (new PostController)->deletePost($app));
});
$app->get('/post/{user_id}', function($user_id) {
    echo json_encode( (new PostController)->getPosts($user_id));
});

$app->get('/profile/{user}', function($user) {
    echo json_encode( (new ProfileController)->getUserInfo($user));
});

$app->get('/profile/image/{user_id}', function($user_id) {
    echo json_encode( (new ProfileController)->getUserImage($user_id));
});



/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
