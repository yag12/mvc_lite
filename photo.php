<?php
include dirname(__FILE__) . "/lib/Bootstrap.php";

$offset = 10;
$pgnum = !empty($_GET['pgnum']) ? $_GET['pgnum'] : 1;
$limit = ($pgnum - 1) * $offset;

$rows = $db->limit($limit, $offset)->select('photo');
$paginator = $db->getPaginator($pgnum);

$bootstrap->setParam('paginator', $paginator);
$bootstrap->setParam('rows', $rows);
$bootstrap->render('photo.php');
$bootstrap->output();
