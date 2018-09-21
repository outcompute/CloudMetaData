<?php
/**
 * The GCP class file.
 *
 * This fetches the metadata from the Google Cloud meta data service
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\Provider;

class GCP extends AbstractProvider
{
    # As reported at : https://cloud.google.com/compute/docs/storing-retrieving-metadata
    const BASE = 'http://metadata.google.internal/computeMetadata/v1/';

    public function __construct() {}

    public function get() {
        $response = array();

        # These are the top level keys.
        $keys = array('instance/', 'project/');
        foreach($keys as $key) {
            $value = $this->_recurse(self::BASE, $key);
            if($value == NULL)
                return NULL;
            $response[trim($key, '/')] = $value;
        }

        return $response;
    }

    private function _recurse($url, $key) {
	$curlOpts = array(
		        array(
        			'option' => CURLOPT_HTTPHEADER,
                                'value' => array('Metadata-Flavor: Google')
                        )
        );
        $key = trim($key);
        $metadata = array();

        $url .= $key;
        if(substr($key, -1) == '/') {
            $response = $this->_parseValue($this->connect($url, $curlOpts));
            if(is_array($response)) {
                foreach($response as $row) {
                    $metadata[trim($row, '/')] = $this->_recurse($url, $row);
                }
            } else {
                $metadata = $response;
            }
        } else {
            $metadata = $this->_parseValue($this->connect($url, $curlOpts));
        }
        return $metadata;
    }

    private function _parseValue($value) {
        $value = trim($value);
        $startEnd = substr($value, 0, 1).substr($value, -1);
        if($startEnd == '[]' || $startEnd == '{}')
            # If value is a JSON string ...
            return json_decode($value, true);
        else {
            # ... else it is either a solitary string, or a listing of keys.
            $value = explode("\n", $value);
            # If a single entry in the array, but ends with '/', then it is expandable, and hence won't be returned as a string
            if(count($value) == 1 && substr($value[0], -1) != '/')
                return $value[0];
            else
                return $value;
        }
    }
}

?>