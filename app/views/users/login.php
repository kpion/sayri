<p>Przykładowi userzy:</p>
<ul>
	<li>login: user1 hasło: user1</li>
	<li>login: user2 hasło: user2</li>
</ul>
<form method='post' class="pure-form pure-form-stacked">
	<fieldset>
	<div class="row"><label>Login:</label><input type='text' name='login'></div>
	<div class="row"><label>Hasło:</label><input type='text' name='password'></div>
	<div><input type='submit' class='pure-button pure-button-primary' value='Login' name='submit'></div>
	</fieldset>
</form>	
<script>
	$(function(){
		$('form input[name=login]').focus();
	})
</script>
	
