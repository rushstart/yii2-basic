<?php

return [
    'id' => 'app-console',
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        //Create migration in module extention -
        //yii migrate/ --migrationPath=coderius/comments/migrations create_comments_table
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
            ],
        ],
    ],
];

