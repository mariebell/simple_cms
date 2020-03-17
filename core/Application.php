<?php
/**
 * Application
 * アプリケーションの構築
 */

abstract class Application
{
  public $debug = false;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;

  public function __construct($debug = false)
  {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  protected function setDebugMode($debug)
  {
    if ($debug) {
      $this->debug = true;
      ini_set('display_errors', 1);
      error_reporting(-1);
    } else {
      $this->debug = false;
      ini_set('display_errors', 0);
    }
  }

  public function initialize()
  {
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->db_manager = new DbManager();
    $this->router = new Router($this->registerRoutes());
  }

  protected function configure()
  {

  }

  //対応するパスを取得する
  abstract public function getRootDir();

  //対応するパスを指定する
  abstract protected function registerRoutes();

  /**
   * 各ステートやインスタンスの取得
   */

  public function isDebugMode()
  {
    return $this->debug;
  }

  public function getRequest()
  {
    return $this->request;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function getSession()
  {
    return $this->session;
  }

  public function getDbManager()
  {
    return $this->db_manager;
  }

  public function getControllerDir()
  {
    return $this->getRootDir . '/controllers';
  }

  public function getViewDir()
  {
    return $this->getRootDir . '/views';
  }

  public function getModelDir()
  {
    return $this->getRootDir . '/models';
  }

  public function getWebDir()
  {
    return $this->getWebDir . '/web';
  }
}