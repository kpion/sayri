# Sayri

DON'T USE IT IN CURRENT VERSION :) It will soon be rewritten. 

A dead simple PHP MVC Framework.

It was created for people who don't understand Dependendcy Injection Containers and for some reason don't want to learn it. Also not PS4 autoload ready.

Only for very very simple small projects.

For everything else, please take a look at one of the following:

Micro frameworks: 

http://www.slimframework.com/

Full stack frameworks:

https://github.com/symfony/symfony

https://github.com/laravel/laravel

---

# Features
  - MVC architecture
  - Built-in authentication
  - Partial views

# Installation
The project will soon be composer-ready, right now however you can [download it](https://github.com/konradpapala/foundation/releases/ "adf")

# Example
Sayri comes with a few example controllers and views. For example Homepage is served by app\controllers\HomepageController.php

And admin homepage is served by app\controllers\AdminHomepageController.php - let's look at it.

If you installed Foundation in a foundation directory, then After loggin in (admin admin) you can open http://localhost/foundation/admin to see it.

Here's the AdminHomepageController controller:

    class AdminHomepageController extends  \app\core\AdminController{
    	
    	public function actionIndex(){
    	    //an example of passing values to a view, this will be passed to a View::get method
    	    $data=['firstName']='John';
    	    //an example of passing an error to a view
    	    View::error('An error occured');
    	    //an example of adding a Javascript file to a view
    	    View::js('file.js');
    		return View::get('admin/homepage/homepage',$data);
    	}
    	
    	protected function hasAccess(){
    		if(!parent::hasAccess())
    			return false;//no access by the rules defined in parrent class
    		//we can now further restrict the access
    		return true;
    	}
    }

This class extends AdminController in which we already set access rules:

	protected function setAccess(){
		parent::setAccess();
		//full access to all controllers for admin role
		$this->allow('admin','*');
	}
	
Althought in the hasAccess() method above we can further restrict the access.

In actionIndex() method, we load our view:

    return View::get('admin/homepage/homepage',$data);

which looks like this:
    
    @include ('templates\admin\header')
    <p>This is admin's homepage view</p>
    
    <div>
    	<p>Partial view test:</p>
    	@include ('admin\homepage\partialTest',['message'=>'Welcome to Sayri framework.']);
    </div>
    
    @include ('templates\admin\footer')

As you can see we include a header, footer and a partialTest view - with one parameter. This partialTest view looks like this:

    <div class='message'>
        <?php
        $passedArray=$parameters[0];
        echo "<p>{$passedArray['message']}</p>";
        ?>
    </div>



