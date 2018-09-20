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
            default:
            break;
        }

        return parent::get($token);
    }
}

?>
