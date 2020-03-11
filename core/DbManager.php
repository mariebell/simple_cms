<?php
/**
 * DbManager
 * データベース接続情報の管理
 */
class DbManager
{
  protected $repository_connection_map = [];

  public function connect($name, $params)
  {
    $params = array_merge([
      'dsn' => null,
      'user' => '',
      'password' => '',
      'options' => [],
    ], $params);

    //PHP Data Object
    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->connections[$name] = $con;
  }

  /**
   * 接続
   */
  public function getConnection($name = null)
  {
    if (is_null($name)) {
      //配列の内部ポインタが示す値を取得
      return current($this->connections);

      return $this->connections[$name];
    }
  }

  //データベース接続先を追加
  public function setRepositoryConnectionMap($repository_name, $name)
  {
    $this->repository_connection_map[$repository_name] = $name;
  }

  public function getConnectionForRepository($repository_name)
  {
    if (isset($this->repository_connection_map[$repository_name])) {
      $name = $this->repository_connection_map[$repository_name];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }

    return $con;
  }
}