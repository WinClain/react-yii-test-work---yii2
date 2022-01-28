<?php

namespace frontend\controllers;

use frontend\models\Post;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use frontend\services\PostService;
use Yii;
use Exception;

/**
 * Site controller
 */
class RestController extends Controller
{

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    'Origin'                           => ['http://localhost:3000'],
                    'Access-Control-Request-Method'    => ['POST','GET'],
                    'Access-Control-Request-Headers'   => ['X-Wsse','Content-Type'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,
                ],

            ],
        ];
    }

    public function actionGetPosts()
    {
        $request = Json::decode(Yii::$app->request->getRawBody());
        $response = [];
        try{
            $posts = [];
            if(isset($request['params'])){
                $posts = (new PostService())->getAll($request['params']);
            }else{
                $posts = (new PostService())->getAll();
            } 
                $response['status'] = 'success';
                $response['posts'] = $posts;
        }catch(Exception $e){
            $response['status'] = 'false';
            $response['error'] = $e->getMessage();

        }
        return Json::encode($response);
    }

    public function actionCreatePost()
    {
        $response = [];
        $request = Json::decode(Yii::$app->request->getRawBody());
        try{
            (new PostService())->createPost($request);
            $response['status'] = 'success';
        }catch(Exception $e){
            $response['status'] = 'false';
            $response['error'] = $e->getMessage();

        }
        return Json::encode($response);
    }

    public function actionDeleteAll()
    {
        $response = [];
        try{
            (new PostService())->deleteAll();
            $response['status'] = 'success';
        }catch(Exception $e){
            $response['status'] = 'false';
            $response['error'] = $e->getMessage();
        }
        return Json::encode($response);
    }
}
