<?php

namespace frontend\services;

use Yii;
use frontend\models\Post;
use DateTime;
use Exception;

class PostService
{
    public function getAll(string $params = null)
    {
        $query = (new Post())->find();
        $posts = [];
        if($params){
            switch ($params){
                case 'random':
                    $posts = $query->all();
                    shuffle($posts);
                    break;
                case 'minute':
                    $end_time = (new DateTime())->setTimestamp(strtotime('now'));
                    $start_time = clone $end_time;
                    $start_time->modify('-1 min');
                    $posts = $query
                                ->where(['between', 'created_at', $start_time->format('d.m.Y H:i:s'), $end_time->format('d.m.Y H:i:s') ])
                                ->orderBy(['created_at'=>SORT_DESC])
                                ->all();

                    break;
                case 'old_to_now':
                    $posts = $query
                        ->orderBy(['created_at'=>SORT_ASC])
                        ->all();
                    break;
                case 'default':
                    $posts = $query->all();
                    break;
            }
        }else{
            $posts = $query->all();
        }

        return $posts;
    }

    public function createPost(array $params)
    {
        $post = new Post();
        $post->title = $params['title'];
        $post->content = $params['content'];
        if($post->save()){
            return true;
        }else{
            return false;
        }
    }

    public function deleteAll()
    {
        (new Post())->deleteAll();
    }
}
