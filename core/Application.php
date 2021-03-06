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
  protected $login_action = [];

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
    return $this->getRootDir() . '/controllers';
  }

  public function getViewDir()
  {
    return $this->getRootDir() . '/views';
  }

  public function getModelDir()
  {
    return $this->getRootDir() . '/models';
  }

  public function getWebDir()
  {
    return $this->getWebDir . '/web';
  }

  /**
   * クライアントのリクエストに対応しレスポンスを返却
   */
  public function run()
  {
    try {
      $params = $this->router->resolve($this->request->getPathInfo());
      if ($params === false) {
        throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
      }
      $controller = $params['controller'];
      $action = $params['action'];

      $this->runAction($controller, $action, $params);

    } catch (HttpNotFoundException $e) {
      $this->render404Page($e);
    } catch (UnauthorizedActionException $e) {
      list($controller, $action) = $this->login_action;
      $this->runAction($controller, $action);
    }
    $this->response->send();
  }

  /**
   * 対応するアクションを呼び出す
   */
  public function runAction($controller_name, $action, $params = [])
  {
    $controller_class = ucfirst($controller_name) . 'Controller';

    $controller = $this->findController($controller_class);
    if ($controller === false) {
      throw new HttpNotFoundException($controller_class . ' controller was not found');
    }

    $content = $controller->run($action, $params);

    $this->response->setContent($content);
  }

  protected function findController($controller_class)
  {
    if (!class_exists($controller_class)) {
      $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
      if (!is_readable($controller_file)) {
        return false;
      } else {
        require_once $controller_file;

        if (!class_exists($controller_class)) {
          return false;
        }
      }
    }
    return new $controller_class($this);
  }

  /**
   * 404ページ
   */
  protected function render404Page($e)
  {
    $this->response->setStatusCode(404, 'Not Found');
    $message = $this->isDebugMode() ? $e->getMessage() : 'Page Not Found.';
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $this->response->setContent($message);
  }
}