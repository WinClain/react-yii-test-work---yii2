<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    private $title;
    private $content;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            ['created_at', 'default', 'value' => date('d.m.Y H:i:s',strtotime('now'))],
        ];
    }

}
