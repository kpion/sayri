<?php
View::css('teeeeest');
?>
<div class='message'>
<?php
$passedArray=$parameters[0];
echo "<p>{$passedArray['message']}</p>";
?>
</div>