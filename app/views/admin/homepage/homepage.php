@include ('templates\admin\header')
<p>This is admin's homepage view</p>

<div>
	<p>Partial view test:</p>
	@include ('admin\homepage\partialTest',['message'=>'Welcome to the Sayri framework.']);
</div>

@include ('templates\admin\footer')