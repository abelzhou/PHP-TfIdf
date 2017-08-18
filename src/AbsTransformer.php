<?php
/**
 *
 * Author: abel
 * Date: 17/8/18
 * Time: 16:23
 */

namespace AbelZhou\TfIdf;

abstract class AbsTransformer {

    protected $_document_count;
    protected $_sort;

    public function addContext($documents) {
        if (!is_array($documents)) {
            $documents = array($documents);
        }
        foreach ($documents as $document) {
            if (!($document instanceof TiDocument)) {
                continue;
            }
            $this->_document_count++;
            $doc_tf = $document->getTF();
            foreach ($doc_tf as $key => $tf) {
                $this->_sort[$key][] = $document->getUnid();
                $this->addSort($document, $key, $tf);
            }
            $this->addDocument($document);
        }
    }

    /**
     * get tf-idf
     * @param TiDocument $document
     * @return array
     */
    public function fit_transform(TiDocument $document) {
        $doc_tf = $document->getTF();
        $result_tfidf = array();
        foreach ($doc_tf as $key => $tf) {
            $tf["idf"] = log($this->_document_count / (count($this->_sort[$key]) + 1));
            $tf["tfidf"] = $tf['idf'] * $tf['tf'];
            $result_tfidf[] = $tf;
        }
        return $result_tfidf;
    }


    /**
     * 在增加单个文档中埋点
     * @param TiDocument $document
     * @return mixed
     */
    abstract function addDocument(TiDocument $document);

    /**
     * 在增加单个文档中统计分类埋点
     * @param TiDocument $document
     * @param $tf_key
     * @param $tf
     * @return mixed
     */
    abstract function addSort(TiDocument $document, $tf_key, $tf);

}