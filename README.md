# CloudMetaData
A simple PHP library to fetch instance meta data from the link local address of http://169.254.169.254 from within an instance.
  - Supports [AWS][awsmetadata], [Azure][azuremetadata] & [DigitalOcean][dometadata].
  - Results can be cached in a file.
  - Always refreshes metadata after a restart.

The 169.254.169.254 is a link-local address and you can read up more about them [here][wikilinklocal].


### Installation
Add this line to your composer.json file,
```json
"outcompute/cloudmetadata": "1.4.0"
```
and run.
```sh
$ composer update
```


### How to use
Without cache
```php
<?php
include_once('vendor/autoload.php');

$metaObject = new OutCompute\CloudMetaData\MetaData();
$metaData = $metaObject->get();
var_export($metaData);
?>
```

With cache (only 'File' available at the moment)
```php
<?php
include_once('vendor/autoload.php');

$cache = OutCompute\CloudMetaData\CacheFactory::factory('File');
$cache->directory = dirname(__FILE__);
$metaObject = new OutCompute\CloudMetaData\MetaData($cache);
$metaData = $metaObject->get('json');
var_export($metaData);
?>
```


### TODO

 - Add other cloud providers, eg: Google Cloud, Linode, etc.
 - Add test cases
 - General improvements


License
----

MIT

   [awsmetadata]: <http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-instance-metadata.html>
   [azuremetadata]: <https://docs.microsoft.com/en-us/azure/virtual-machines/windows/instance-metadata-service>
   [dometadata]: <https://developers.digitalocean.com/documentation/metadata/>
   [wikilinklocal]: <https://en.wikipedia.org/wiki/Link-local_address>
