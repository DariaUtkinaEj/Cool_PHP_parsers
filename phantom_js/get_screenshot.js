var page = require('webpage').create();
page.viewportSize = {
    width: 1920,
    height: 1080
};
page.open('http://www.amazon.com', function() {

    setTimeout(function() {
        page.render('amazon.png');
        phantom.exit();
    }, 200);
});