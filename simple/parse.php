<?php
define('PER_BLOCK', '10');
//set db connection - for example, db - test_parser, table - 'articles'
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'test_parser');

require_once "simple_html_dom.php";
require_once "db.php";

$url = 'http://ananaska.com/vse-novosti/';


$db = new DB (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// get params from CLI
if (isset($argv[1])) {
    $action = argv[1];
} else {
    echo 'No action';
    exit;
}
// just get only links to articles and save to db
if ($action == 'catalog') {
    getArticlesLinksFromCatalog($url);
} elseif($action == 'articles'){
        $tmp_uniq = md5(uniqid().time());
        $db->query("update articles set tmp_uniq = '{$tmp_uniq}' where tmp_uniq is null limit ".PER_BLOCK);
       // $db->query('select url from articles where date_parsed is null limit 1'));
            while(true){
                $articles = $db->query("select url from articles where tmp_uniq = '{$tmp_uniq}'");
                if(!$articles) {
                    echo "All done";
                    exit;
                }
                foreach ($articles as $article){
                    getArticleData($article[0]['url']);
                }
            }
}

/**
 * @param $url
 * @return mixed
 */
function getArticleData($url){
    global $db;

    $article = file_get_html($url);

    $h1 = $db->escape($article->find('h1', 0)->innertext);
    $content = $db->escape($article->find('article', 0)->innertext);



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


    $html = file_get_html($url);
    foreach($html->find('a.read-more-link') as $link_to_article) {
        $article_url = $db->escape($link_to_article->href);
        $sql = "
        insert ignore into articles
        set url = '{$article_url}'
        ";
        $db->query($sql);

        echo $link_to_article->href . PHP_EOL;

    }
    if ($next_link = $html->find('a.next', 0)) {
        getArticlesLinksFromCatalog($next_link->href);
    }
}
getArticlesLinksFromCatalog($url);
