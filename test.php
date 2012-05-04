<?php
include_once('MysqlDb.php');
$db = new MysqlDb('localhost', 'root', '', 'flashcardapp');
$db->where('front', 'Animal');
print_r($db->get('APUSH'));
