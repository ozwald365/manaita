<?php

namespace Manaita\Lib;

class SearchResult {

    protected $result = array();

    public function get_all_contents() {
        if(isset($this->result['contents'])) {
            return $this->result['contents'];
        } else {
            return array();
        }
    }

    public function get_all_relations() {
        if(isset($this->result['relations'])) {
            return $this->result['relations'];
        } else {
            return array();
        }
    }

    public function get_content($index = 0) {
        $contents = $this->get_all_contents();
        if (empty($contents)) {
            return array();
        }

        if (isset($contens[$index])) {
            return $contens[$index];
        } else {
            return array();
        }
    }

    public function get_relation($index = 0) {
        $relations = $this->get_all_relations();
        if (empty($relations)) {
            return array();
        }

        if (isset($relations[$index])) {
            return $relations[$index];
        } else {
            return array();
        }
    }

}
