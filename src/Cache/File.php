<?php
/**
 * The File cache class file.
 *
 * @author     outcompute
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace OutCompute\CloudMetaData\Cache;

class File extends AbstractCache
{
    # Best practice is to provide absolute path to $directory, and the directory should be writable
    public $directory;

    public function __construct() {}

    # $filename is the key in this case
    public function get($filename) {
        $filePath = rtrim($this->directory, '/').'/'.$filename;
        if(file_exists($filePath))
            return json_decode(file_get_contents($filePath), true);
        else
            return null;
    }

    # $filename is the key in this case
    public function set($filename, $data) {
        if(
            !is_dir($this->directory) ||
            !is_writable($this->directory)
        ) {
            throw new \Exception(__CLASS__." : {$this->directory} doesn't exist or is not writable.");
        }
        file_put_contents(rtrim($this->directory, '/').'/'.$filename, json_encode($data, JSON_PRETTY_PRINT));
    }
}
?>
