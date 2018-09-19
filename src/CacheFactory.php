<?php
/**
 * The CacheFactory class file.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData;

class CacheFactory {
	static public function factory($cacheName) {
		$cacheClass = __NAMESPACE__.'\Cache\\'.$cacheName;
		return new $cacheClass();
	}
}
?>
