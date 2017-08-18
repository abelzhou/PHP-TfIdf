<?php
/**
 *
 * Author: abel
 * Date: 17/8/17
 * Time: 14:46
 */

namespace AbelZhou\TfIdf;


use AbelZhou\Tree\TrieTree;

class TiDocument {
    private $_hit_word_count = 0;
    private $_hit_word_arr = array();
    private $_unid = 0;

    /**
     *
     * Document constructor.
     * @param $content  the Documents content
     * @param unid|null the unique id for the document content.
     */
    public function __construct(TrieTree $tree, $content, $unid) {
        $hit_word_arr = $tree->search($content);
        if (empty($hit_word_arr)) {
            return;
        }

        $this->_hit_word_arr = $hit_word_arr;
        //统计文档关键词总数
        foreach ($hit_word_arr as $word) {
            $this->_hit_word_count += $word['count'];
        }
        $this->_unid = $unid;
        unset($tree);
        unset($content);
    }

    /**
     * 获得TF权重
     * @return array
     */
    public function getTF() {
        $tf_arr = array();
        foreach ($this->_hit_word_arr as $key => $word) {
            $word_str = $word['word'];
            $count = $word['count'];
            $tf = $count / $this->_hit_word_count;
            $tf_arr[$key] = array(
                'word' => $word_str,
                'count' => $count,
                'tf' => $tf
            );
        }
        return $tf_arr;
    }

    public function getUnid(){
        return $this->_unid;
    }


}