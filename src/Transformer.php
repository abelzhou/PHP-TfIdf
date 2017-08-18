<?php
/**
 *
 * Author: abel
 * Date: 17/8/17
 * Time: 14:39
 */

namespace AbelZHou\TfIdf;


class Transformer extends AbsTransformer {

    /**
     * 开放读取分类文章数据权限
     * @return mixed
     */
    protected function getSort(){
        return $this->_sort;
    }

    /**
     * 开放读取当前文章总数权限
     * @return mixed
     */
    protected function getDocumentCount(){
        return $this->_document_count;
    }

    /**
     * 在增加单个文档中埋点
     * @param TiDocument $document
     * @return mixed
     */
    function addDocument(TiDocument $document) {
        // TODO: Implement addDocument() method.
        //这里可以做一些存储数据到Redis的事情
    }

    /**
     * 在增加单个文档中统计分类埋点
     * @param TiDocument $document
     * @param $tf_key
     * @param $tf
     * @return mixed
     */
    function addSort(TiDocument $document, $tf_key, $tf) {
        // TODO: Implement addSort() method.
        //这里也可以做一些存储数据到Redis的事情
    }
}