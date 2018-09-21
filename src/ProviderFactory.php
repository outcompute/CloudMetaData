<?php
/**
 * The ProviderFactory class file.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData;

class ProviderFactory {
	static public function factory($providerName) {
		$providerClass = __NAMESPACE__.'\Provider\\'.$providerName;
		return new $providerClass();
	}
}
?>
