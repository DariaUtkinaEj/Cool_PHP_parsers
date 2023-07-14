<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Batman's Site Screenshot Generator</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">
    <div class="col-md-6">
        <h1>Batman's Site Screenshot Generator</h1>
        <form id="form-screenshot" role="form" onsubmit="return sendForm();">
            <div class="form-group">
                <label for="url">Site URL to get screenshot:</label>
                <input type="text" name="url" class="form-control">
            </div>
            <div class="form-group">
                <label for="resolution">Screen Resolution:</label>
                <select name="resolution" class="form-control">
                    <option value="375x667">320x568</option>
                    <option value="375x667">375x667</option>
                    <option value="375x667">375x812</option>
                    <option value="1024x768">1024x768</option>
                    <option value="1280x960">1280x960</option>
                    <option value="1280x1024">1280x1024</option>
                    <option value="1920x1080">1920x1080</option>
                </select>
            </div>
            <div class="form-group">
                <label for="resolution">User Agent:</label>
                <select name="user_agent" class="form-control">
                    <?php
                        $tmp = file_get_contents('user-agents.txt');
                        $user_agents = explode(PHP_EOL, $tmp);
                        foreach ($user_agents as $user_agent){?>
                            <option value="<?=$user_agent?>"><?=$user_agent?></option>
                        <?php }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Get Screenshot</button>
        </form>
    </div>
    <div class="col-md-6" id="screenshots"></div>
</div>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/scripts.js"></script>

<script>
function sendForm(){
    // Get form fields
    params = $('#form-screenshot').serialize();
    // "In progress" message
    $('#screenshots').html('<p>Waiting for screenshot...</p>');
    // Send request for screenshot
    $.post('/get_screenshot.php', params, function (data) {
            $('#screenshots').html('<img class="screen_preview" src="'+data+'" />');
        });
    return false;
}
</script>
<style>
    .screen_preview{
        max-width: 100%;
        padding:10px;
        border: 1px solid #d9edf7;
    }
</style>
</body>
</html>