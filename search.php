<?php
    // TODO 削除(php.iniにinclude path追加)
    set_include_path(get_include_path() . PATH_SEPARATOR . './lib/');

    require_once('./lib/search.php');

    use \Manaita\Lib\Search;

    $keyword  = get('keyword') ?: '鶏肉'; // 適当な初期値
    $page_num = get('page') ?: 1; // 1～... 初期値：1
    $type     = get('type') ?: 'google';

    $search_result = null;
    if ($type == 'google') {
        $search_result = Search::search_google($keyword, $page_num, Search::GGL_TARGET_COOKPAD);
    } else {
        // 増えたら追加
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>まな板</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./css/search.css">
<link rel="stylesheet" href="./css/common.css">
<link rel="shortcut icon" href="">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <div id="header">
        <ul class="clearfix">
            <li id="header_menu"><a href="/"><img src="./img/icon_menu.png" alt="メニューボタン" /></a></li>
            <li id="header_logo"><img src="./img/header_logo.png" alt="スマートまな板 ヘッダー" /></li>
            <li id="header_setting"><a href="/"><img src="./img/icon_haguruma.png" alt="設定メニュー" /></a></li>
        </ul>
    </div>
    <div id="input_area">
        <form action="<?php echo basename(__FILE__); ?>" method="GET">
            <input type="text" name="keyword" />
            <input type="hidden" name="page" value="1" />
            <?php // 今はgoogle一択 ?>
            <input type="hidden" name="type" value="google" />
            <input type="submit" name="run" value="検索" />
        </form>
        <div id="relations" class="clearfix">
            <img id="relation_icon" src="http://placehold.jp/30x30.png" alt="ダミー" />
<?php
    $relation_url_base = get_search_url_base();
    foreach($search_result->get_all_relations() as $rel) {
        echo '<a class="relation" href="' . sprintf($relation_url_base, $rel, 0, $type) . '">' . $rel . '</a>';
    }
?>
        </div>
    </div>
    <div id="result_area">
<?php
    foreach($search_result->get_all_contents() as $content) {
        $url = 'http://' . $content['url'];

        $title = '<h3><a href="%s">%s</a></h3>';
        $thumnail = '<a href="%s" class="thumnail" ><img src="http://placehold.jp/100x100.png" alt="ダミー" /></a>';
        $descliption = '<p class="desc">%s</p>';

        echo '<div class="content clearfix">';
        echo sprintf($title, $url, $content['title']);
        echo sprintf($thumnail, $url);
        echo sprintf($descliption, $content['description']);
        echo '</div>';
    }
?>    
    </div>
    <div id="pager_area" class="clearfix">
<?php
    $next_url = get_next_page_url();
    $prev_url = get_prev_page_url();
    if ($prev_url) {
        echo '<a id="prev_page" href="' . $prev_url . '">前へ</a>';
    } else {
        echo '<p id="prev_page_disable">前へ</p>';
    }
    echo '<a id="next_page" href="' . $next_url . '">次へ</a>';
?>
    </div>
</body>
</html>


<?php
    
function get($param) {
    if ($param === null)
        return null;

    $get  = (isset($_GET[$param])) ? $_GET[$param] : null;
    $post = (isset($_POST[$param])) ? $_POST[$param] : null;
    
    return $get ?: $post;
}


function make_contents($saerch_result) {
    $contents = $search_result->get_all_contents();

    if (empty($contents)) {
        // 0件
    } else {
        $format = <<<EOS
<ul class="search_content">
    <li class="title">%s</li>
    <li class="url">%s</li>
    <li class="desc">%s</li>
    <li class="thumbnail">%s<li>
</ul>
EOS;

    }
}

function get_next_page_url() {
    global $keyword, $page, $type;

    $next_page = sprintf(get_search_url_base(), $keyword, $page + 1, $type);

    return $next_page;
}

function get_prev_page_url() {
    global $keyword, $page, $type;

    if ($page <= 0) {
        return null;
    }

    $prev_page = sprintf(get_search_url_base(), $keyword, $page - 1, $type);

    return $prev_page;
}

function get_search_url_base() {
    return $current_url = ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]) . '?keyword=%s&page=%s&type=%s';
}

?>
