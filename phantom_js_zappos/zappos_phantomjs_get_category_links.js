/*
    zappos_phantomjs_get_category_links.js
 */

var page = require('webpage').create();
var fs = require('fs');

page.open('https://www.zappos.com/site-map', function (status) { // success
    var categories_links_json = page.evaluate(function () {
        // Пользовательский код
        var all_links = [];
        var links = document.querySelectorAll('._1el6l a');
        [].forEach.call(links, function(link){
            all_links.push({
                'title': link.text,
                'href': link.getAttribute('href')
            });
        });

        var all_links_json = JSON.stringify(all_links);

        return all_links_json;
    });

    fs.write('zappos_categories_links.json', categories_links_json);

    phantom.exit();
});

