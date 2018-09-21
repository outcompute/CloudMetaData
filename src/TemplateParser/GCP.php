<?php
/**
 * The GCP class file.
 *
 * This extends the Base parser and implements any GCP specific functionality
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\TemplateParser;

class GCP extends Base
{
	private $_metaData;

	public function __construct($metaData) {
		parent::__construct($metaData);
		$this->_metaData = $metaData;
	}

    public function get($token) {
        switch($token) {
        	case '{{metadata.provider}}':
        		return 'gcp';
        	break;
        	case '{{metadata.id}}':
        		return $this->_metaData['instance']['id'];
        	break;
        	case '{{metadata.region}}':
        		return strtolower($this->_metaData['instance']['zone']);
        	break;
        	case '{{metadata.name}}':
        		return strtolower($this->_metaData['instance']['name']);
        	break;
        	case '{{metadata.attributes}}':
        		return strtolower($this->_metaData['instance']['attributes']);
        	break;
        	case '{{devices.interfaces}}':
        		return array(
        			'public' => array(
        				'ip' => $this->_metaData['instance']['network-interfaces'][0]['access-configs'][0]['external-ip']
        			),
        			'private' => array(
        				'ip' => $this->_metaData['instance']['network-interfaces'][0]['ip']
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
