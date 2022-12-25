<?php

/**
 * 1.
 */

shell_exec('phantomjs.exe zappos_phantomjs_get_category_links.js');
$json_file_name = 'zappos_categories_links.json';
if(file_exists($json_file_name)){
    $cat_links = json_decode(file_get_contents($json_file_name), true);
}

print_r($cat_links);

/**
 * 2.
 */

