<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>Title - Web page</title>
	<script type="text/javascript" src="js/autoload.js"></script>
</head>
<body>

<div id="m-top">
	<div class="title">Top title</div>
</div>
<div>
	<table width="100%" class="tbl-list">
		<tr height="30" align="center">
			<td><a href="index.php">메뉴1</a></td>
			<th><a href="model.php">메뉴2</a></th>
			<td><a href="photo.php">메뉴3</a></td>
			<th>메뉴4</th>
			<td>메뉴5</td>
		</tr>
	</table>
</div>
<div id="m-contents">
	<?php echo $this->contents; ?>
</div>

</body>
</html>
