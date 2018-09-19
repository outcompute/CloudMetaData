<?php
/**
 * The AbstractCache class file.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\Cache;

abstract class AbstractCache
{
    abstract public function get($key);
    abstract public function set($key, $data);
}
?>
