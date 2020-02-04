<?php


namespace app\modules\admin;


use app\modules\admin\controllers\UserController;
use Yii;
use yii\web\NotFoundHttpException;

class Module extends \yii\base\Module
{
    public $layout = 'main';

    /**
     * {@inheritdoc}
     * @throws NotFoundHttpException
     */
    public function beforeAction($action)
    {
        if(($action->controller instanceof UserController && $action->id === 'login') || !Yii::$app->user->isGuest){
            return parent::beforeAction($action);
        }
        throw new NotFoundHttpException("Страница не найдена.");
    }
}