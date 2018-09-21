<?php
/**
 * The MetaData class file.
 *
 * The constructor fetches and prepares the metadata in the private data property.
 * The constructor also refreshes the metadata cache if there has been a restart,
 * in case interface addresses or any other property has been re-assigned.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData;

class MetaData
{
	# Seconds till which metadata can be considered fresh
	const EXPIRESIN = 300;

	private $_data;
	private $_provider;

	public function __construct($cache = NULL, $providerFilter = NULL) {
		$this->_data = NULL;
		$this->_provider = NULL;
		$cacheKey = 'cloudmetadata';

		# List of supported providers to try against
		$providers = array('AWS', 'Azure', 'DigitalOcean', 'GCP');

		if($cache != NULL) {
			$metadata = $cache->get($cacheKey);
			if($metadata != NULL) {
				$uptime = floatval(@file_get_contents('/proc/uptime'));
				if(
					# Metadata is fresh and ...
					$metadata['created'] + self::EXPIRESIN > time() &&
					# ... there has been no restart since we have this data
					$uptime > self::EXPIRESIN
				) {
					$this->_data = $metadata['data'];
					$this->_provider = $metadata['provider'];
				}
			}
		}

		if($this->_data == NULL) {
			if($providerFilter != NULL && is_array($providerFilter))
				$providers = array_intersect($providers, $providerFilter);

			foreach($providers as $provider) {
				$this->_data = call_user_func(array(ProviderFactory::factory($provider), 'get'));

				if($this->_data != NULL) {
					# The first match has been found, and return after saving to cache, if cache object is set
					if($cache != NULL)
						$cache->set($cacheKey, array('created' => time(), 'provider' => $provider, 'data' => $this->_data));
					$this->_provider = $provider;
					break;
				}
			}
		}
	}

	public function get($format = 'array', $templateName = NULL) {
		$response = array('provider' => $this->_provider, 'metadata' => $this->_data);
		$templatedResponse = NULL;

		if($templateName != NULL) {
			$parsedResponseObject = NULL;
			$templatePath = rtrim(__DIR__, '/').'/../templates/'.basename($templateName);
			if(file_exists($templatePath))
				$templateObject = json_decode(file_get_contents($templatePath), true);
			if($templateObject != NULL) {
				$parserObject = TemplateParserFactory::factory($response['provider'], $response['metadata']);
				$parsedResponseObject = $this->_parseTemplate($templateObject, $parserObject);
			}
			$response = $parsedResponseObject;
		}

		switch($format) {
			case 'json':
				return json_encode($response, JSON_PRETTY_PRINT);
				break;
			default:
				return $response;
				break;
		}
	}

	private function _parseTemplate($templateObject, $parserObject) {
		foreach($templateObject as $k => $v) {
			switch(gettype($v)) {
				case "string":
					$templateObject[$k] = $parserObject->get($v);
				break;
				case "array":
				case "object":
					$templateObject[$k] = $this->_parseTemplate($v, $parserObject);
				break;
				default:
					$templateObject[$k] = $v;
				break;
			}
		}
		return $templateObject;
	}
}
?>
