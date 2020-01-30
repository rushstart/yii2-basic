<?php

return [
    'name' => 'Web application',
    'id' => 'app-web',
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ]
];
