<?php
/**
 * bootstrap.php
 * アプリケーションの起動と初期設定
 */
require 'core/ClassLoader.php';

//オートロード設定
$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();