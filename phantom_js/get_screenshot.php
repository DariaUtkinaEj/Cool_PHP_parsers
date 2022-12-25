<?php

$url = urldecode($_POST['url']);
$resolution = $_POST['resolution'];
// TODO: security checks
$user_agent = $_POST['user_agent'];
$resolution = explode('x', $resolution);
$width = (int)array_shift($resolution);
$height = (int)array_shift($resolution);

// TODO: uniqid()
$hash = md5($url)."_{$width}x{$height}";
$image_file_name = "{$hash}.png";
// Generate JS for PhantomJS
$script = "
var page = require('webpage').create();

page.settings.userAgent = '{$user_agent}';

page.viewportSize = {
    width: {$width},
    height: {$height}
};

page.open('{$url}', function() {
    setTimeout(function() {
        page.render('{$image_file_name}');
        phantom.exit();
    }, 1000);
});
";
$script_file_name = $hash.'.js';
file_put_contents($script_file_name, $script);
// Execute PhantomJS with script
shell_exec("phantomjs.exe {$script_file_name}");
// TODO unlink() used js file
// Echo src for <img>
echo '/'.$image_file_name;