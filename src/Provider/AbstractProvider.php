<?php
/**
 * The AbstractProvider class file.
 *
 * @author     outcompute
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace OutCompute\CloudMetaData\Provider;

abstract class AbstractProvider
{
    abstract public function get();

    # This method may need serious work in future versions depending on compatibility issues
    public function connect($url, $options = array())
    {
        if(function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            $errorStr = curl_error($curl);
            curl_close($curl);

            if($info['http_code'] == 200)
                return $result;
        } else {
            throw new \Exception("OutCompute\CloudMetaData\Provider\AbstractProvider : curl not installed. Failed to connect to $url");
        }
    }
}
?>
