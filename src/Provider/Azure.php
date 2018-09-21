<?php
/**
 * The Azure class file.
 *
 * This fetches the metadata from the Azure meta data service
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\Provider;

class Azure extends AbstractProvider
{
    # As reported at: https://docs.microsoft.com/en-us/azure/virtual-machines/windows/instance-metadata-service
    const BASE = 'http://169.254.169.254/metadata/instance?api-version=2017-08-01';

    public function __construct() {}

    public function get() {
            return json_decode(
                    $this->connect(
                            self::BASE,
                            array(
                                    array(
                                            'option' => CURLOPT_HTTPHEADER,
                                            'value' => array('Metadata: true')
                                    )
                            )
                    ),
                    true
            );
    }
}

?>