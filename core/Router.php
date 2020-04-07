<?php
/**
 * Router
 * ルーティング
 */
class Router
{
  protected $routes;

  public function __construct($definitions)
  {
    $this->routes = $this->compileRoutes($definitions);
  }

  /**
   * 動的パラメータを含むルーティング定義に、変換用の正規表現を仕込む
   */
  public function compileRoutes($definitions)
  {
    $routes = [];
    foreach ($definitions as $url => $params) {
      $tokens = explode('/', ltrim($url, '/')); //パスを分解（トークンと呼ぶ）
      //トークンを取得する
      foreach ($tokens as $i => $token) {
        //動的パラメータに対応
        if (0 === strpos($token, ':')) {
          $name = substr($token, 1);
          $token = "(?P<" . $name . ">[^/]+)";
        }
        $tokens[$i] = $token;
      }
      $pattern = '/'.implode('/', $tokens);
      $routes[$pattern] = $params;
    }

    return $routes;
  }

  /**
   * ルーティング定義をもとに、パスの解決
   */
  public function resolve($path_info)
  {
    if ('/' !== substr($path_info, 0, 1)) {
      $path_info = '/' . $path_info;
    }
    foreach ($this->routes as $pattern => $params) {
      if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
        $params = array_merge($params, $matches);

        return $params;
      }
    }
    return false;
  }
}