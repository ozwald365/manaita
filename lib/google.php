<?php

namespace Manaita\Lib;

require_once('searchresult.php');
require_once('phpQuery-onefile.php');
//require_once('log.php');

//use \Manaita\Lib\Util\Log;
use \phpQuery;

class Google extends SearchResult {

    public function parse($body) {
        $this->fetch_search_result(phpQuery::newDocument($body));
    }

    protected function fetch_search_result($pq_document) {
        $this->result = array();

        $this->result['contents'] = $this->fetch_contents($pq_document);
        $this->result['relations'] = $this->fetch_relactions($pq_document);
    }

    private function fetch_contents($pq_document) {
        $contents = array();

        foreach($pq_document['div#ires li.g'] as $ires) {
            $content_info = array();
            $pq_ires = pq($ires);

            $content_info['title'] = $pq_ires->find('h3.r a')->text();
            $content_info['description'] = $pq_ires->find('span.st')->html();
            $content_info['url'] = preg_replace('/類似ページ/', '', $pq_ires->find('div.kv')->text());

            $contents[] = $content_info;
        }

        return $contents;
    }

    private function fetch_relactions($pq_document) {
        $relations = array();

        foreach($pq_document['p._Bmc'] as $rel) {
            $relations[] = pq($rel)->text();
        }
        return $relations;
    }

}


?>
