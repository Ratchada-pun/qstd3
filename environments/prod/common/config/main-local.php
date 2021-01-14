<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'charset' => getenv('DB_CHARSET', 'utf8'),
            'enableSchemaCache' => YII_ENV_PROD,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => YII_DEBUG ? 'localhost' : 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'db_his' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=203.154.158.48;dbname=db_banbung',
            'username' => 'Andaman',
            'password' => 'b8888888',
            'charset' => 'utf8',
        ],
    ],
];
