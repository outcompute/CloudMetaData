<?php
/**
 * The CacheFactory class file.
 *
 * @author     outcompute
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace OutCompute\CloudMetaData;

class CacheFactory {
	static public function factory($cacheName) {
		$cacheClass = __NAMESPACE__.'\Cache\\'.$cacheName;
		return new $cacheClass();
	}
}
?>
