var page = require('webpage').create();
var system = require('system');
var args = system.args;

page.onError = function(){}

page.open('http://translate.google.com/#'+args[1]+'/'+args[2]+'/'+encodeURIComponent(args[3]),
 var translated_text = page.evaluate(function(){
     return document.querySelector('#result_box').innerText;
 });
    console.log(translated_text);
    phantom.exit();
});