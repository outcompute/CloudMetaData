# CloudMetaData
A simple PHP library to fetch instance meta data from the link local address of http://169.254.169.254 from within an instance.
  - Supports [AWS][awsmetadata], [Azure][azuremetadata], [DigitalOcean][dometadata] & [Google Cloud][gcpmetadata].
  - Results can be cached in a file.
  - Always refreshes metadata after a restart.

The 169.254.169.254 is a link-local address and you can read up more about them [here][wikilinklocal].


### Installation
Add this line to your composer.json file,
```json
"outcompute/cloudmetadata": "1.5.0"
```
and run.
```sh
$ composer update
```
In case you don't want to use composer, you'll have to include all the files, something like this:
```php
include_once('src/Cache/AbstractCache.php');
include_once('src/Cache/File.php');
include_once('src/CacheFactory.php');
include_once('src/Provider/AbstractProvider.php');
include_once('src/Provider/AWS.php');
include_once('src/Provider/Azure.php');
include_once('src/Provider/DigitalOcean.php');
include_once('src/Provider/GCP.php');
include_once('src/ProviderFactory.php');
include_once('src/TemplateParser/Base.php');
include_once('src/TemplateParser/AWS.php');
include_once('src/TemplateParser/Azure.php');
include_once('src/TemplateParser/DigitalOcean.php');
include_once('src/TemplateParser/GCP.php');
include_once('src/TemplateParserFactory.php');
include_once('src/MetaData.php');
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

### Templated Responses
If you've cross cloud deployments and use the metadata in any way, then it'd help to have the metadata in a consistent manner.
Support has been added for templates which allows you to specify templates and tokens. A few sample tokens have been provided.
Common and provider agnostic tokens are processed in `src/TemplateParser/Base.php` whereas provider specific tokens are parsed in their specific handlers present in `src/TemplateParser`.

The templates have to be stored in the templates directory, and the file name provided as the second argument to `OutCompute\CloudMetaData\MetaData()::get()`.
```php
<?php
include_once('vendor/autoload.php');

$metaObject = new OutCompute\CloudMetaData\MetaData();
$metaData = $metaObject->get('json', 'basic.json');
var_export($metaData);
?>
```
The tokens don't have to follow the heirarchy from the templates they are included in and can be any string as long as they are handled in any parser. The initial set of supported tokens seem to follow the heirarchy in basic.json with a dot(.) as a separator, but that is not a strict requirement.

However, if you're contributing to the repository then it'd be great if the tokens followed some structure.

### TODO

 - Add other cloud providers, eg: Linode, etc.
 - Add test cases
 - General improvements
 - Supporting templates in varied configurations and extending the set of supported tokens

License
----

MIT

   [awsmetadata]: <http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-instance-metadata.html>
   [azuremetadata]: <https://docs.microsoft.com/en-us/azure/virtual-machines/windows/instance-metadata-service>
   [dometadata]: <https://developers.digitalocean.com/documentation/metadata/>
   [gcpmetadata]: <https://cloud.google.com/compute/docs/storing-retrieving-metadata>
   [wikilinklocal]: <https://en.wikipedia.org/wiki/Link-local_address>
