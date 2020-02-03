<?php


namespace app\modules\admin;


class Module extends \yii\base\Module
{
    public $layout = 'main';

    public function beforeAction($action)
    {
        dpm(\Yii::$app->user->identity);
        return parent::beforeAction($action);
    }
}