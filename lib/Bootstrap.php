<?php
class Bootstrap
{
	protected $config = array();
	protected $scripts = 'scripts';
	protected $params = array();
	public $db = null;
	public function __construct()
	{
		$this->setHeader();
		$this->setRequire();
		$this->setDefine();
		$this->setConnect();
	}

	static public function startup()
	{
		return new Bootstrap;
	}

	/*
	* @Desc : Set Header
	* @Param : void
	* @Return : void
	*/
	private function setHeader()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		header("Content-Type:text/html; charset=utf-8");
	}

	/*
	* @Desc : Set Require
	* @Param : void
	* @Return : void
	*/
	private function setRequire()
	{
		$lib = dirname(__FILE__);

		require_once $lib . '/Function.php';
		require_once $lib . '/Mysqli.php';
		require_once $lib . '/MobileDetect.php';
		require_once $lib . '/FileUpload.php';
		require_once $lib . '/ThumbImage.php';

		require_once $lib . '/config.php';
		if(!empty($config))
		{
			$this->config = $config;
		}
	}

	/*
	* @Desc : Set DB Connect
	* @Param : void
	* @Return : void
	*/
	private function setConnect()
	{
		if(!empty($this->config['database']))
		{
			$this->db = new MysqliDB($this->config['database']);
		}
	}

	/*
	* @Desc : Set Define
	* @Param : void
	* @Return : void
	*/
	private function setDefine()
	{
		$mobile = new MobileDetect;
		$isMobile = $mobile->isMobile();
		if($isMobile === true)
		{
			$this->scripts = 'mobiles';
		}

		define('ROOT', dirname(dirname(__FILE__)));
		define('IS_MOBILE', $isMobile);
	}

	/*
	* @Desc : get Contents
	* @Param : mixed $file
	* @Param : array $data
	* @Return : mixed
	*/
	private function getContents($file = null, $data = array())
	{
		$contents = null;
		if(!empty($data))
		{
			extract($data);
		}

		if(is_file($file))
		{	
			ob_start();
			require $file;
			$contents = ob_get_clean();
		}
		else
		{
			// Error Message
			throw new Exception("File Not Found : " . $file, 404);
		}

		return $contents;
	}

	/*
	* @Desc : render
	* @Param : mixed $name
	* @Param : mixed $scope
	* @Return : void
	*/
	public function render($name = null, $scope = 'contents')
	{
		$view = ROOT . '/' . $this->scripts . '/view/' . $name;
		$this->$scope = $this->getContents($view, $this->params);
	}

	/*
	* @Desc : partials
	* @Param : mixed $name
	* @Param : array $data
	* @Param : mixed $scope
	* @Return : void
	*/
	public function partials($name = null, $data = array(), $scope = null)
	{
		$view = ROOT . '/' . $this->scripts . '/partials/' . $name;
		if($scope === null)
		{
			return $this->getContents($view, $data);
		}

		$this->$scope = $this->getContents($view, $data);
	}

	/*
	* @Desc : set Param
	* @Param : mixed $key
	* @Param : mixed $value
	* @Return : void
	*/
	public function setParam($key = null, $value = null)
	{
		if(!empty($key) && !empty($value))
		{
			$this->params[$key] = $value;
		}
	}

	/*
	* @Desc : output
	* @Param : mixed $layout
	* @Return : void
	*/
	public function output($layout = 'index.php')
	{
		$layoutFile = ROOT . '/' . $this->scripts . '/layout/' . $layout;
		if(is_file($layoutFile))
		{
			echo $this->getContents($layoutFile, $this->params);
		}
	}
}

session_start();

$bootstrap = Bootstrap::startup();

// Mysql
$db = &$bootstrap->db;
