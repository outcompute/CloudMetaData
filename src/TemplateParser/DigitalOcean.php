<?php
/**
 * The DigitalOcean class file.
 *
 * This extends the Base parser and implements any DigitalOcean specific functionality
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\TemplateParser;

class DigitalOcean extends Base
{
    private $_metaData;

    public function __construct($metaData) {
        parent::__construct($metaData);
        $this->_metaData = $metaData;
    }

    public function get($token) {
        switch($token) {
            case '{{metadata.provider}}':
                return 'digitalocean';
            break;
            case '{{metadata.id}}':
                return $this->_metaData['droplet_id'];
            break;
            case '{{metadata.region}}':
                return strtolower($this->_metaData['region']);
            break;
            case '{{metadata.name}}':
                return strtolower($this->_metaData['hostname']);
            break;
            case '{{metadata.attributes}}':
                return $this->_metaData['tags'];
            break;
            case '{{devices.interfaces}}':
                return array(
                    'public' => array(
                        'ip' => $this->_metaData['interfaces']['public'][0]['ipv4']['ip_address']
                    ),
                    'private' => array(
                        'ip' => $this->_metaData['interfaces']['public'][0]['anchor_ipv4']['ip_address']
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
