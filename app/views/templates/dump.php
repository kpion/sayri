<?php
if(!empty($templateDumps)) foreach($templateDumps as $templateDump){?>
	<div class="message">
		<p><b>Dump</b></p>
		<?php Utils::var_dump($templateDump)?>
	</div>
<?php } ?>