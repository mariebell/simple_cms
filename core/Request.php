<?php
/**
 * Request
 * HTTPリクエスト情報の制御
 */
class Request
{
  public function isPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  /**
   * GETパラメータをキーから取得する
   * @param $name
   */
  public function getGet($name, $default = null)
  {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $default;
  }

  /**
   * POSTデータをキーから取得する
   * @param $name
   */
  public function getPost($name, $default = null)
  {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    return $default;
  }

  /**
   * サーバのホスト名を取得
   */
  public function getHost()
  {
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }
  
  /**
   * HTTPS通信かどうか
   */
  public function isSsl()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }
    return false;
  }

  /**
   * リクエストURLを取得
   */
  public function getRequestUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * パスのベース部分を取得
   * /hoge/fuga/index.php?piyo=1 -> /hoge/fuga
   */
  public function getBaseUrl()
  {
    $script_name = $SERVER['SCRIPT_NAME']; //実際に処理されるファイル /hoge/fuga/index.php
    $request_uri = $this->getRequestUri(); //表示上のURL /hoge/fuga

    if (0 === strpos($request_uri, $script_name)) {
      return $script_name;
    } else if (0 === strpos($request_uri, dirname($script_name))) {
      return rtrim(dirname($script_name), '/'); //ファイル名.phpの親にあたるパスを返却
    }

    return '';
  }

  /**
   * パスからベース部分を除いたものを取得
   */
  public function getPathInfo()
  {
    $base_url = $this->getBaseUrl();
    $request_uri = $this->getRequestUri();

    //クエリ部分を捨てる
    if (false !== ($pos = strpos($request_url, '?'))) {
      $request_uri = substr($request_uri, 0, $pos); 
    }
    //ベースとなるURL以下のパスを取得
    $path_info = (string)substr($request_uri, strlen($base_url));

    return $path_info;
  }
}