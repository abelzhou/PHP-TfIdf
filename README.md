# PHP-IfIdf
这是一个关于文章关联度统计的PHP库

## 应用场景
> 由于可能用于大量文章的使用场景。
> 希望项目能够既使用在CLI模式[常驻内存|脚本等]当中，同时采用一些其他的存贮机制比如redis，也可以应用在CGI模式当中。
> 文章分类并没有采用分词手段，分类词库需要手动添加，个人认为目前的分词精准程度都有待提高，并且这些年产生了很多"新词"，都是分词系统所识别不了的。

## Version
- 1.0 项目可用

## Install
```shell
    composer require abelzhou/php-tfidf
```


## 用例
```php
use AbelZhou\TfIdf\TiDocument;
use AbelZhou\TfIdf\AbsTransformer;
use AbelZhou\TfIdf\Transformer;
use AbelZhou\Tree\TrieTree;


//构造分类树
$tree = new TrieTree();
$tree->append("设计图");
$tree->append("大哭");
$tree->append("衣服");
$tree->append("友谊");

$contants = array(
    "我的设计图有点儿问题，我得修改一下。",
    "因为这个比较难看的设计图，她大哭了起来。",
    "老妈命令我把这些过时的衣服处理掉。",
    "幸运的是，爆炸没有造成人员伤亡，老小区的设计图有问题。但是很多人丢掉了衣服。",
    "为了准备这场考试，他花费了大量的时间和精力联系了很多设计图纸。",
    "在公共场合随地吐痰是很粗俗的行为。",
    "杯子装得太满了，果汁都溢出来了，洒到了衣服上。",
    "她趁母亲接电话时偷偷溜出去了。",
    "友谊是从互相信任开始的。"
);


$loadmem = 0;
$documents = array();
for ($i = 0; $i < count($contants); $i++) {
    $documents[] = new TiDocument($tree, $contants[$i], $i);
}

$transformer = new Transformer();
//添加文档书
$transformer->addContext($documents);
$res = $transformer->fit_transform($documents[3]);
var_dump($res);
```
上述脚本的执行结果为
```php
array(2) {
  [0]=>
  array(5) {
    ["word"]=>
    string(9) "设计图"
    ["count"]=>
    int(1)
    ["tf"]=>
    float(0.5)
    ["idf"]=>
    float(0.58778666490212)
    ["tfidf"]=>
    float(0.29389333245106)
  }
  [1]=>
  array(5) {
    ["word"]=>
    string(6) "衣服"
    ["count"]=>
    int(1)
    ["tf"]=>
    float(0.5)
    ["idf"]=>
    float(0.81093021621633)
    ["tfidf"]=>
    float(0.40546510810816)
  }
}

```

## 扩展
如果需要将数据贮存在Redis中，可以继承AbsTransformer抽象类，自行实现addDocument和addSort方法。
所有新加入的文章会调用addDocument方法，分析出的分类标签也会逐一调用addSort方法。
这两个埋点的主要目的,是为了在添加大量文章时，避免重复循环而造成不必要的性能开销。
必要时请使用"@"忽略错误。

例如：
```php
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
        //@$redis->incr($document->getUnid(),1);
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
```
