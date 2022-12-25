<?php

//set db connection - example;
// db test_parser, table 'articles'
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'test_parser');

//lib for parsing
require_once "simple_html_dom.php";
require_once "db.php";
//site url for parsing
$url = 'http://ananaska.com/vse-novosti/';
$db = new DB (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/**
 * @param $url
 * @return mixed
 */
function getArticleData($url){
    global $db;

    $article = file_get_html($url);

    $h1 = $db->escape($article->find('h1', 0)->innertext);
    $content = $db->escape($article->find('article', 0)->innertext);

    // $data = array(
    //    'h1' => $h1,
    //     'content' => $content
    //  );

    $data = compact('h1', 'content');

    $sql = "
    update articles
      set h1 ='{$h1}',
          content = '{$content}',
          date_parsed = NOW()
      where url = '{$url}'
    ";

    $db->query($sql);
    return $data;
}

/**
 * @param $url
 */
  function getArticlesLinksFromCatalog($url){
      global $db;

   echo PHP_EOL.$url.PHP_EOL.PHP_EOL;

    //get page
    $html = file_get_html($url);
    //get each article link
    foreach($html->find('a.read-more-link') as $link_to_article) {
        // add to db each article link
        $article_url = $db->escape($link_to_article->href);
        $sql = "
        insert ignore into articles
        set url = '{$article_url}'
        ";
        $db->query($sql);
        // parse and save current article by current link
        getArticleData($link_to_article->href);

        echo $link_to_article->href . PHP_EOL;

    }
    if ($next_link = $html->find('a.next', 0)) {
        getArticlesLinksFromCatalog($next_link->href);
    }
}
getArticlesLinksFromCatalog($url);