<?php
/**
 * The AWS class file.
 *
 * This extends the Base parser and implements any AWS specific functionality
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\TemplateParser;

class AWS extends Base
{
    private $_metaData;

    public function __construct($metaData) {
        parent::__construct($metaData);
        $this->_metaData = $metaData;
    }

    public function get($token) {
        switch($token) {
            case '{{metadata.provider}}':
                return 'aws';
            break;
            case '{{metadata.id}}':
                return $this->_metaData['meta-data']['instance-id'];
            break;
            case '{{metadata.region}}':
                return strtolower($this->_metaData['dynamic']['instance-identity']['document']['region']);
            break;
            case '{{metadata.name}}':
                return '';
            break;
            case '{{metadata.attributes}}':
                return array();
            break;
            case '{{devices.interfaces}}':
                return array(
                    'public' => array(
                        'ip' => $this->_metaData['meta-data']['public-ipv4']
                    ),
                    'private' => array(
                        'ip' => $this->_metaData['meta-data']['local-ipv4']
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
