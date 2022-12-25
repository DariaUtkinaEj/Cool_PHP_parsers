var all_links = [];
var links = document.querySelectorAll('._1el6l a');
[].forEach.call(links, function(link){
    all_links.push({
        'title': link.text,
        'href': new URL(link.getAttribute('href'),
            'https://www.zappos.com').href
    });
});
// https://developer.mozilla.org/ru/docs/Web/API/URL/URL
var all_links_json = JSON.stringify(all_links);