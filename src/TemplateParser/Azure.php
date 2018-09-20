<?php
/**
 * The Azure class file.
 *
 * This extends the Base parser and implements any Azure specific functionality
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\TemplateParser;

class Azure extends Base
{
	private $_metaData;

	public function __construct($metaData) {
		parent::__construct($metaData);
		$this->_metaData = $metaData;
	}

    public function get($token) {
        switch($token) {
        	case '{{metadata.provider}}':
        		return 'azure';
        	break;
        	case '{{metadata.id}}':
        		return $this->_metaData['compute']['vmId'];
        	break;
        	case '{{metadata.region}}':
        		return strtolower($this->_metaData['compute']['location']);
        	break;
        	case '{{metadata.name}}':
        		return strtolower($this->_metaData['compute']['name']);
        	break;
        	case '{{metadata.attributes}}':
				$tagPairs = explode(';', $this->_metaData['compute']['tags']);
				$tags = array();
				foreach($tagPairs as $keyValue) {
					list($key, $value) = explode(':', $keyValue);
					$tags[strtolower($key)] = strtolower($value);
				}
				return $tags;
        	break;
        	case '{{devices.interfaces}}':
        		return array(
        			'public' => array(
        				'ip' => $this->_metaData['network']['interface'][0]['ipv4']['ipAddress']['publicIpAddress']
        			),
        			'private' => array(
        				'ip' => $this->_metaData['network']['interface'][0]['ipv4']['ipAddress']['privateIpAddress']
        			)
        		);
        	break;
            default:
		        return parent::get($token);
            break;
        }
    }
}

?>
