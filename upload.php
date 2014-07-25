<?php
include dirname(__FILE__) . "/lib/Bootstrap.php";

$uid = $_POST['uid'];
$model = $_POST['model'];

$uploadPath = dirname(__FILE__) . '/image/photo';
$upload = new FileUpload($uploadPath, array('jpg', 'gif', 'png'));
$upload->upload();
$file = $upload->getFiles();

if(!empty($file[0]['name']))
{
	$fileName = $file[0]['name'];
	$ext = end(explode(".", $fileName));
	$name = (microtime(true) * 10000) . "." . $ext;
	if(!rename($uploadPath . '/' . $fileName, $uploadPath . '/' . $name))
	{
		$name = $fileName;
	}

	$db->fields(array(
		'uid' => $uid,
		'model' => $model,
		'photo_name' => $name,
		'regdate' => mktime(),
	))->insert('photo');
}

header('Location: model.php');
