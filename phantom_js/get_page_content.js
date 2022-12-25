var page = require('webpage').create();
page.open('http://www.amazon.com', function() {
    setTimeout(function() {
        console.clear();
        console.log(page.content);
        phantom.exit();
    }, 200);
});