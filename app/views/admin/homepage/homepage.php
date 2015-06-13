@include ('templates\admin\header')
<p>This is admin's homepage view</p>

<div>
	<p>Partial view test:</p>
	@include ('admin\homepage\partialTest',['message'=>'Welcome to Foundation framework.']);
</div>

@include ('templates\admin\footer')