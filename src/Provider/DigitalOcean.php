<?php
/**
 * The DigitalOcean class file.
 *
 * This fetches the metadata from the DigitalOcean meta data service
 *
 * @author     outcompute
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace OutCompute\CloudMetaData\Provider;

class DigitalOcean extends AbstractProvider
{
    # As reported at: https://developers.digitalocean.com/documentation/metadata/
    const BASE = 'http://169.254.169.254/metadata/v1.json';

    public function __construct() {}

    public function get() {
        return json_decode($this->connect(self::BASE), true);
    }
}

?>
