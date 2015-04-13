<?php

include_once('phpQuery-onefile.php');

$URL_FORMAT = 'https://www.google.co.jp/search?q=%s+inurl:%s&start=%s';
$TARGET_GOOGLE = 'cookpad.com/recipe/';
$TYPE_GOOGLE = 'google';


$keyword = get('keyword');
$type = get('type');
$page = (get('page') !== null) ? get('page') : '0';

$site = '';
if ($type === $TYPE_GOOGLE) {
    $site = $TARGET_GOOGLE;
} else {
}

$url = sprintf($URL_FORMAT, $keyword, $site, $page * 10);

$html_utf8 = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'SJIS');
$dom = phpQuery::newDocument($html_utf8);

//echo $html_utf8;
//exit(0);

$search_result = $dom['div#ires li.g'];
//var_dump($search_result->html());
$content = array();
foreach($search_result as $ret) {
    $tmp = array();
    $li_g_selecter = pq($ret);
    $tmp['title'] = $li_g_selecter->find('h3.r a')->text();
    $tmp['desc'] = $li_g_selecter->find('span.st')->html();
    $tmp['url'] = $li_g_selecter->find('div.kv')->text();

    $content[] = $tmp;
}

$relations = array();
foreach($dom['p._Bmc'] as $rel) {
    $relations[] = pq($rel)->text();
}

//var_dump($content);
//var_dump($relations);


function get($param) {
    if (isset($_GET[$param])) {
        return $_GET[$param];
    } else {
        return null;
    }
}

$data = array(
    'contents' => $content,
    'relations' => $relations,
);

echo json_encode($data);


?>
