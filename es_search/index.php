<?php
use EsSearch as Es;
spl_autoload_register(function($name){
    if (file_exists($file = __DIR__ . '/' . $name . '.php')) {
        require_once $file;
    }
});

try {
    $resp = EsSearch::getInstance()->createIndex('book', date('Y-m'), ['name' => '平凡的世界']);
    if ($resp) {
        var_dump('成功');
    } else {
        var_dump('失败');
    }
    /*
     *
     * Array
(
    [_index] => book
    [_type] => _doc
    [_id] => 2020-04
    [_version] => 1
    [result] => created
    [_shards] => Array
        (
            [total] => 2
            [successful] => 1
            [failed] => 0
        )

    [_seq_no] => 1
    [_primary_term] => 1
)

     * */
} catch(\Exception $e) {
    echo $e->getMessage();
}
