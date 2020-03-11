<?php
/**
 * ClassLoader
 * クラス名に対応するファイルを自動ロードする
 */
class ClassLoader
{
  protected $dirs;

  public function dir()
  {
    print_r($this->dirs);
  }

  //オートロードの登録
  public function register()
  {
    spl_autoload_register([$this, 'loadClass']);
  }

  //探索対象のディレクトリに追加する
  public function registerDir($dir)
  {
    $this->dirs[] = $dir;
  }

  //クラスをロードする
  public function loadClass($class)
  {
    foreach ($this->dirs as $dir) {
      $file = $dir . '/' . $class . '.php';
      if (is_readable($file)) {
        require $file;
        return;
      }
    }
  }

}