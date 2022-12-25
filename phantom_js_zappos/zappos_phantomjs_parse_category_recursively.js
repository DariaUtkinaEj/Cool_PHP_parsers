var fs = require('fs');
var all_products = [];

/**
 * @param url
 */
function parseCatalogPage(url) {
    console.log('Start parsing with ' + url + "\n");
    var page = require('webpage').create();
    page.open(url, function (status) {
        if(status == 'success'){

            var page_links = page.evaluate(function(){
                var result = {products_links: [], next_page_url: null};
                if(document.querySelectorAll('link[rel="next"]').length){
                    result.next_page_url = document.querySelector('link[rel="next"]').getAttribute('href');
                }

                var links = document.querySelectorAll('article a');
                [].forEach.call(links, function(link){
                    result.products_links.push('http://www.zappos.com' + link.getAttribute('href'));
                });
                return result;
            });

            all_products = all_products.concat(page_links.products_links);
            fs.write('products_links_cat_XXX.json', JSON.stringify(all_products));
            console.log(all_products.length + "\n");
            if(page_links.next_page_url){
                parseCatalogPage('http://www.zappos.com' + page_links.next_page_url);
                page.close();
            } else {
                console.log('No more next page. Exit');
                phantom.close();
            }
        }
    });
}
// TODO: get this URL from PHP
parseCatalogPage('https://www.zappos.com/women-shoes/CK_XAToCmg3AAQHiAgMBGAc.zso?s=isNew/desc/productPopularity/asc/&p=1');