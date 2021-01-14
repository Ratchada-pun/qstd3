<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.1.*'],
        'generators' => [ //here
            'migrations' => [ // generator name
                'class' => 'common\themes\homer\gii\migration\Generator', // generator class
                'templates' => [ //setting for out templates
                    'DB-Migration' => '@common/themes/homer/gii/migration/default', // template name => path to template
                ]
            ]
        ],
    ];
}

return $config;
