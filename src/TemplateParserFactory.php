<?php
/**
 * The TemplateParserFactory class file.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData;

class TemplateParserFactory {
	static public function factory($providerName, $metaData) {
		$parserClass = __NAMESPACE__.'\TemplateParser\\'.$providerName;
		return new $parserClass($metaData);
	}
}
?>
