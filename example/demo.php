<?php
/**
 *
 * Author: abel
 * Date: 17/8/18
 * Time: 12:23
 */

require "../src/Document.php";
require "../src/AbsTransformer.php";
require "../src/Transformer.php";
require "../vendor/autoload.php";

use AbelZhou\TfIdf\TiDocument;
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

//开始计时
$time = microtime(true);
$transformer = new Transformer();
//添加文档书
$transformer->addContext($documents);
$res = $transformer->fit_transform($documents[3]);
$time = microtime(true) - $time;
var_dump($res);