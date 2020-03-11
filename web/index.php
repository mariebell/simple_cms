<?php
require '../bootstrap.php';


print_r($loader->dir());
$req = new Request();
print_r($req->getBaseUrl());
