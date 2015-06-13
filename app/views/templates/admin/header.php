<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/css/common.css">
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/css/admin.css">
		
		
		<script type="text/javascript" src="<?=Url::base()?>assets/js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="<?=Url::base()?>assets/js/jquery-ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=Url::base()?>assets/js/jquery-ui/jquery-ui.min.css">
				
		<script type="text/javascript" src="<?=Url::base()?>assets/js/common.js"></script>
		<?php if(!empty($templateCssFiles)) foreach($templateCssFiles as $file) echo "<link rel='stylesheet' type='text/css' href='$file'>";?>
		<?php if(!empty($templateJsFiles)) foreach($templateJsFiles as $file) echo "<script type='text/javascript' src='$file'></script>";?>	
		<script>
			var curUserId=<?=Auth::isLoggedIn()?Auth::cur()['id']:'0'?>;
			var baseUrl='<?=Url::base()?>';
		</script>
	</head>
	<header class="pure-menu pure-menu-horizontal">
		<ul class="pure-menu-list">
			<li class="pure-menu-item"><a class="pure-menu-link" href='<?=Url::base('')?>'>Main site</a></li>
			<li class="pure-menu-item"><a class="pure-menu-link" href='<?=Url::base('users/logout')?>'>Logout</a></li>
		</ul>
	</header>	
	<body>
		<div class="page">
			<?php if(!empty($templateErrors)){
				foreach($templateErrors as $templateError){
			?>
				<div class="error">
					<p><?=$templateError?></p>
				</div>
				<?php } ?>
			<?php } ?>
			<?php if(!empty($templateMessages)){
				foreach($templateMessages as $templateMessage){
			?>
				<div class="message"><p>
					<p><?=$templateMessage?></p>
				</div>
				<?php } ?>
			<?php } ?>
			