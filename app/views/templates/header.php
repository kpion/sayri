<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/css/common.css">
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/css/front.css">
		
		
		<script type="text/javascript" src="<?=Url::base()?>assets/js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="<?=Url::base()?>assets/js/jquery-ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/js/jquery-ui/jquery-ui.min.css">
				
		<script type="text/javascript" src="<?=Url::base()?>assets/js/common.js"></script>
		<?php foreach($templateCssFiles as $file) echo "<link rel='stylesheet' type='text/css' href='$file'>";?>
		<?php foreach($templateJsFiles as $file) echo "<script type='text/javascript' src='$file'></script>";?>	
		<script>
			var curUserId=<?=Auth::cur()['id']?>;
			var baseUrl='<?=Url::base()?>';
		</script>
	</head>
	<header class="pure-menu pure-menu-horizontal">
		<ul class="pure-menu-list">
			<li class="pure-menu-item"><a class="pure-menu-link" href='<?=Url::base('users/login')?>'>Zaloguj się</a></li>
			<li class="pure-menu-item"><a class="pure-menu-link" href='<?=Url::base('users/logout')?>'>Wyloguj się</a></li>
			<?php
			if(Auth::isLoggedIn() && Auth::cur()->is('admin')){
			?>
			<li class="pure-menu-item"><a class="pure-menu-link" href='<?=Url::base('admin')?>'>Admin</a></li>
			<?php } ?>
		</ul>
	</header>	
	<body>
		<div class="page">
			<?php if(!empty($templateError)){?>
			<div class="error"><p>
				<?=$templateError?>
			</div></p>
			<?php } ?>
			<?php if(!empty($templateMessage)){?>
			<div class="message"><p>
				<?=$templateMessage?>
			</div></p>
			<?php } ?>
