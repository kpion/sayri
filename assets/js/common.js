"use strict";
//by IE się nie pluł
if(typeof console=='undefined'){
	console={log:function(){}};
}

var Utils={
	/*
	 replaces all occurences in a string
	 */
	replaceAll:function(find, replace, str) {
		return str.replace(new RegExp(find, 'g'), replace);
	},
	
	/**
	A very simple function to create templates, example:
	var data={id:1,name:'Elizabeth'};
	var html="<div id='^id^'>Name: ^name^</div>";
	html=Utils.template(html,data);
	console.log(html);
	@param string varWrapper - what defines a variable, by default: ^
   */
	template: function (pattern,data,varWrapper){
		if(typeof(varWrapper)=='undefined')
			varWrapper='^';
		varWrapper='\\'+varWrapper;//just in case varWrapper is something meaningfull in reg expression
		for(var k in data) {
			pattern=Utils.replaceAll(varWrapper+k+varWrapper,data[k],pattern);
		}
		return pattern;
	}
};