<?php


namespace app\commands;


use app\models\forms\SignupForm;
use app\models\User;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class UserController extends Controller
{
    /**
     * @param $email
     * @param $password
     * @param string $username
     * @return int
     * @throws Exception
     */
    public function actionCreate($email, $password, $username = 'noname')
    {
        $model = new SignupForm([
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'status' => User::STATUS_ACTIVE,
        ]);
        if ($model->signup()) {
            $this->stdout("Пользователь был создан!\n", Console::FG_GREEN);
            return ExitCode::OK;
        } else {
            $this->stdout("Пожалуйста, исправьте следующие ошибки:\n", Console::FG_RED);
            $this->stdout(join("\n", $model->getErrorSummary(true)) . "\n", Console::FG_RED);
            return ExitCode::DATAERR;
        }
    }
}