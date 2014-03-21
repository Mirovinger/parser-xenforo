<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex()
	{
		$urlThread = 'http://9af5766eb2759a49.demo-xenforo.com/130/index.php?threads/some-thread.1/';
		/** @var \app\components\ParserXenforo $dataParse */
		$dataParse = Yii::$app->parser
			->loadUsingCurl($urlThread)
			->createDomDocument()
			->createDomXpath()
			->parseTitle()
			->parseTimeStamp()
			->parseContent()
			->endParse();
		return $this->render('index', ['data' => $dataParse]);
	}

	public function actionAbout()
	{
		return $this->render('about');
	}
}
