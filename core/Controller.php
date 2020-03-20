<?php
/**
 * Controller
 */

 abstract class Controller
 {
   protected $controller_name;
   protected $action_name;
   protected $request;
   protected $response;
   protected $session;
   protected $db_manager;

   public function __construct($application)
   {
     $this->controller_name = strtolower(substr(get_class($this), 0, -10));
     $this->request = $application->getRequest();
     $this->response = $application->getResponse();
     $this->session = $application->getSession();
     $this->db_manager = $application->getDbManager();
   }

   //アクションを実行する
   public function run($action, $params = [])
   {
    $this->action_name = $action;

    $action_method = $action . 'Action';
    //アクションがなければ404
    if (!method_exists($this, $action_method)) {
      $this->forward404();
    }

    //認証が必要で、かつ認証されていなければエラー
    if ($this->needsAuthentication($action) && !$this->session->isAUthenticated()) {
      throw new UnauthorizedActionException();
    }

    $content = $this->action_method($params);

    return $content;
   }

   //出力処理(Viewを生成)
   public function render($variables = [], $template = null, $layout = 'layout')
   {
     $defaults = [
       'request' => $this->request,
       'base_url' => $this->request->getBaseUrl(),
       'session' => $this->session,
     ];
     //ビューを生成
     $view = new View($this->application->getViewDir(), $defaults);

     if (is_null($template)) {
       $template = $this->action_name;
     }
     $path = $this->controller_name . '/' . $template;

     return $view->render($path, $variables, $layout);
   }

   //例外処理を呼び出す
   protected function forward404()
   {
      throw new HttpNotFoundException(
        'Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name
      );
   }

   //認証が必要なアクションかどうか
   protected function needsAuthentication($action)
   {
     if ($this->auth_actions === true ||
      is_array($this->auth_actions) && in_array($action, $this->auth_actions)) {
        return true;
     }
     return false;
   }

   //リダイレクト
   protected function redirect($url)
   {
     if (!preg_match('#https?://#', $url)) {
       $protocol = $this->request->isSsl() ? 'https://' : 'http://';
       $host = $this->request->getHost();
       $base_url = $this->request->getBaseUrl();

       $url = $protocol . $host . $base_url . $url;
     }
     $this->response->setStatusCode(302, 'Found');
     $this->response->setHttpHeader('Location', $url);
   }

   //CSRF対策・トークンをセッションに保存
   protected function generateCsrfToken($form_name)
   {
     $key = 'csrf_tokens/' . $form_name;
     $tokens = $this->session->get($key, []);
     if (count($tokens) >= 10) {
       array_shift($tokens);
     }

     //トークン生成
     $token = sha1($form_name . session_id() . microtime());
     $tokens[] = $token;

     $this->session->set($key, $tokens);

     return $token;
   }

   //CSRF対策・トークンが存在するかチェック
   protected function checkCsrfToken($form_name, $token)
   {
     $key = 'csrf_tokens/' . $form_name;
     $tokens = $this->session->get($key, []);

     if (false !== ($pos = array_search($token, $tokens, true))) {
       unset($tokens[$pos]);
       $this->session->set($key, $tokens);

       return true;
     }

     return false;
   }
 }