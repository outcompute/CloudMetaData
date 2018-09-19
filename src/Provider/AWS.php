<?php
/**
 * The AWS class file.
 *
 * This fetches the metadata from the Amazon meta data service
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\Provider;

class AWS extends AbstractProvider
{
    # As reported at : http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-instance-metadata.html
    const BASE = 'http://169.254.169.254/latest/';

    public function __construct() {}

    public function get() {
        $response = array();

        # These are the top level keys. We need all of them to get information not available at the meta-data endpoint
        # Furthermore we need to segregate them because although AWS documentation does mention that all keys ending in '/'
        # have expansions, the keys reported at http://169.254.169.254/latest/ don't end in '/'
        $keys = array('dynamic/', 'meta-data/', 'user-data/');
        foreach($keys as $key) {
            $value = $this->_recurse(self::BASE, $key);
            if($value == NULL)
                return NULL;
            $response[trim($key, '/')] = $value;
        }

        return $response;
    }

    private function _recurse($url, $key) {
        $key = trim($key);
        $metadata = array();

        $url .= $key;
        if(substr($key, -1) == '/') {
            $response = $this->_parseValue($this->connect($url));
            if(is_array($response)) {
                foreach($response as $row) {
                    $metadata[trim($row, '/')] = $this->_recurse($url, $row);
                }
            } else {
                $metadata = $response;
            }
        } else {
            $metadata = $this->_parseValue($this->connect($url.'/'));
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
