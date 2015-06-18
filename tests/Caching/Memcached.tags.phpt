<?php

/**
 * Test: Nette\Caching\Storages\MemcachedStorage tags dependency test.
 */

use Nette\Caching\Storages\MemcachedStorage;
use Nette\Caching\Storages\FileJournal;
use Nette\Caching\Cache;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!MemcachedStorage::isAvailable()) {
	Tester\Environment::skip('Requires PHP extension Memcache.');
}


$storage = new MemcachedStorage('localhost', 11211, '', new FileJournal(TEMP_DIR));
$cache = new Cache($storage);


// Writing cache...
$cache->save('nette-tags-key1', 'value1', array(
	Cache::TAGS => array('one', 'two'),
));

$cache->save('nette-tags-key2', 'value2', array(
	Cache::TAGS => array('one', 'three'),
));

$cache->save('nette-tags-key3', 'value3', array(
	Cache::TAGS => array('two', 'three'),
));

$cache->save('nette-tags-key4', 'value4');


// Cleaning by tags...
$cache->clean(array(
	Cache::TAGS => 'one',
));

Assert::null($cache->load('nette-tags-key1'));
Assert::null($cache->load('nette-tags-key2'));
Assert::truthy($cache->load('nette-tags-key3'));
Assert::truthy($cache->load('nette-tags-key4'));
