@include ('templates\header');
<p>Przykładowi userzy:</p>
<ul>
	<li>login: admin hasło: admin</li>
	<li>login: mod1 hasło: mod1</li>
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
@include ('templates\footer');
	
