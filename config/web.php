<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
	'id' => 'minimal',
	'basePath' => dirname(__DIR__),
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'components' => [
		'parser' => [
			'class' => 'app\components\ParserXenforo',
			'host' => 'http://73093611c484c1c8.demo-xenforo.com/130/index.php?',
			'username' => 'admin',
			'password' => 'admin',
			'curlOpt' => [
				'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36',
				'header' => [
					'Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/jpeg, image/gif, image/x-xbitmap, */*;q=0.1',
					'Accept-Language: en-US,en;q=0.8,ru;q=0.6,uk;q=0.4',
					'Accept-Charset: Windows-1251, utf-8, *;q=0.1',
					'Accept-Encoding: deflate, identity, *;q=0',
				]
			]
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning', 'info'],
				],
			],
		],
		'db' => $db,
	],
	'params' => $params,
];

return $config;
