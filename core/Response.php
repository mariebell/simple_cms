<?php
/**
 * Response
 * HTTPヘッダ情報とコンテンツの送出
 */
 class Response
 {
    protected $content;
    protected $status_code = 200;
    protected $status_text = 'OK';
    protected $http_headers = [];

    //レスポンスを送出
    public function send()
    {
      header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);

      foreach ($this->http_headers as $name => $value) {
        header($name . ': ' . $value);
      }

      echo $this->content;
    }

    public function setContent($content)
    {
      $this->content = $content;
    }

    public function setHttpHeader($name, $value)
    {
      $this->http_headers[$name] = $value;
    }
 }