<?php
/**
 * @link http://demetrodon.com/
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD 3-Clause License
 */

namespace app\components;
namespace app\components;

use Yii;
use \yii\base\Object;

/**
 * @property string $pathToCookieFile
 * @property string $loginUrl
 * @property string $title
 * @property int $timestamp
 * @property string $content
 *
 * @author Vadim Poplavskiy <im@demetrodon.com>
 */
class ParserXenforo extends Object
{
	/**
	 * Uri to authorize the resource to parse the closed hide theme
	 */
	const REQUEST_URI_LOGIN = 'login/login';
	/**
	 * The file name to save the file cookies
	 */
	const COOKIES_FILE_NAME = 'cookies.txt';
	/**
	 * @var string data parse
	 */
	private $_data;
	/**
	 * @var string information about the host server from which to parse the data
	 */
	public $host;
	/**
	 * @var string username for the authorization in the resource
	 */
	public $username;
	/**
	 * @var string password for the authorization in the resource
	 */
	public $password;
	/**
	 * @var array configuration cURL
	 */
	public $curlOpt;
	/**
	 * @var string title
	 */
	private $_title;
	/**
	 * @var string content
	 */
	private $_content;
	/**
	 * @var int timestamp
	 */
	private  $_timestamp;
	/**
	 * @var \DOMXPath xpath
	 */
	private  $_xpath;
	/**
	 * @var \DOMXPath dom
	 */
	private  $_dom;

	/**
	 * Retrieving configuration cURL
	 * @param $nameOpt string name option for cURL
	 * Maintaining the value of: userAgent and header
	 * @return bool|string|array opt curl
	 */
	protected function getCurlOpt($nameOpt)
	{
		if ($nameOpt !== 'userAgent' && $nameOpt !== 'header') {
			return false;
		}
		return $this->curlOpt[$nameOpt];
	}

	/**
	 * Getting the full url handler request authorization
	 * View as host + url login
	 * @return string url authorization
	 */
	protected function getLoginUrl()
	{
		return $this->host . self::REQUEST_URI_LOGIN;
	}

	/**
	 * Creating a post request string for authentication using cURL
	 * @return string post request
	 */
	protected function createPostRequestForCurl()
	{
		return 'login=' . $this->username . '&password=' . $this->password . '&remember=1';
	}

	/**
	 * Getting the path to the file cookies.
	 * The file is stored application.runtime + cookie file name
	 * @param string $cookieFileName string cookie file name. Defaults is self::COOKIES_FILE_NAME
	 * @return string
	 */
	protected function getPathToCookieFile($cookieFileName = self::COOKIES_FILE_NAME)
	{
		return Yii::getAlias('@app/runtime') . DIRECTORY_SEPARATOR . $cookieFileName;
	}

	/**
	 * Loading data using cURL, transferred on url page for further processing.
	 * @param string $url string url from which the data will be taken
	 * @return \app\components\ParserXenforo
	 * @throws \Exception
	 */
	public function loadUsingCurl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->loginUrl);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getCurlOpt('header'));
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->pathToCookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->pathToCookieFile);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->getCurlOpt('userAgent'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->createPostRequestForCurl());
		$this->_data = curl_exec($ch);
		if (curl_exec($ch) === false) {
			throw new \Exception(curl_errno($ch) . ': ' . curl_error($ch));
		}
		curl_close($ch);

		Yii::info(Yii::t('app', 'Loading data page'));

		return $this;
	}

	/**
	 * @return \app\components\ParserXenforo
	 */
	public function createDomDocument()
	{
		$this->_dom = new \DOMDocument();
		libxml_use_internal_errors(true);
		$this->_dom->loadHTML($this->_data);
		libxml_use_internal_errors(false);

		Yii::info(Yii::t('app', 'Create DomDocument'));

		return $this;
	}

	/**
	 * @return ParserXenforo
	 */
	public function createDomXpath()
	{
		$this->_xpath = new \DOMXPath($this->_dom);

		Yii::info(Yii::t('app', 'Create DomXpath'));

		return $this;
	}

	/**
	 * @return \app\components\ParserXenforo
	 */
	public function parseTitle()
	{
		$xpathQuery = '*//h1';
		$nodes = $this->_xpath->query($xpathQuery, $this->_dom);
		$this->_title = $nodes->item(0)->nodeValue;

		Yii::info(Yii::t('app', 'Parse title'));

		return $this;
	}

	/**
	 * @return \app\components\ParserXenforo
	 */
	public function parseTimestamp()
	{
		$xpathQuery = '*//p[@id="pageDescription"]/a/abbr';
		$nodes = $this->_xpath->query($xpathQuery, $this->_dom);
		$this->_timestamp = $nodes->item(0)->getAttribute('data-time');

		Yii::info(Yii::t('app', 'Parse timestamp'));

		return $this;
	}

	/**
	 * @return \app\components\ParserXenforo
	 */
	public function parseContent()
	{
		$xpathQuery = '*//blockquote[@class="messageText ugc baseHtml"]';
		$nodes = $this->_xpath->query($xpathQuery, $this->_dom);
		$this->_content = $nodes->item(0)->nodeValue;

		Yii::info(Yii::t('app', 'Parse content'));

		return $this;
	}

	/**
	 * @return \app\components\ParserXenforo
	 */
	public function endParse()
	{
		Yii::info(Yii::t('app', 'End parse'));

		return $this;
	}

	/**
	 * @return string title
	 */
	public function getTitle()
	{
		return $this->_title;
	}

	/**
	 * @return int timestamp
	 */
	public function getTimestamp()
	{
		return $this->_timestamp;
	}

	/**
	 * @return string content
	 */
	public function getContent()
	{
		return $this->_content;
	}
} 