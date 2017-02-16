<?php
/**
 * The MetaData class file.
 *
 * The constructor fetches and prepares the metadata in the private data property.
 * The constructor also refreshes the metadata cache if there has been a restart,
 * in case interface addresses or any other property has been re-assigned.
 *
 * @author     outcompute
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace OutCompute\CloudMetaData;

class MetaData
{
	# Seconds till which metadata can be considered fresh
	const EXPIRESIN = 300;

	private $_data;
	private $_provider;

	public function __construct($cache = null, $providerFilter = null) {
		$this->_data = null;
		$this->_provider = null;
		$cacheKey = 'cloudmetadata';

		# List of supported providers to try against
		$providers = array('AWS', 'DigitalOcean');

		if($cache != null) {
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

		if($this->_data == null) {
			if($providerFilter != null && is_array($providerFilter))
				$providers = array_intersect($providers, $providerFilter);

			foreach($providers as $provider) {
				$this->_data = call_user_func(array(ProviderFactory::factory($provider), 'get'));

				if($this->_data != null) {
					# The first match has been found, and return after saving to cache, if cache object is set
					if($cache != null)
						$cache->set($cacheKey, array('created' => time(), 'provider' => $provider, 'data' => $this->_data));
					$this->_provider = $provider;
					break;
				}
			}
		}
	}

	public function get($format = 'array')
	{
		$response = array('provider' => $this->_provider, 'metadata' => $this->_data);
		switch($format) {
			case 'json':
				return json_encode($response, JSON_PRETTY_PRINT);
				break;
			default:
				return $response;
				break;
		}
	}
}
?>
