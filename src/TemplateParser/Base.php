<?php
/**
 * The AbstractParser class file.
 *
 * @author     outcompute
 */

namespace OutCompute\CloudMetaData\TemplateParser;

class Base
{
	private $_metaData;

	public function __construct($metaData) {
		$this->_metaData = $metaData;
	}

    public function get($token) {
    	switch($token) {
    		case '{{devices.cpu.count}}':
    			return (int) shell_exec("grep -c processor /proc/cpuinfo");
    		break;
    		case '{{devices.gpu.count}}':
    			return (int) shell_exec("ls -l /proc/driver/nvidia/gpus | grep -c ^d");
    		break;
    		case '{{devices.memory.total}}':
    			return (float) shell_exec("grep MemTotal /proc/meminfo | awk '{print $2 * 1024 * 1024}'");
    		break;
    		case '{{devices.block.count}}':
    			return (float) shell_exec("blkid | wc -l");
    		break;
    		case '{{users}}':
				$output = trim(shell_exec("ls /home/"));
				$usernames = explode(PHP_EOL, $output);
				$sshKeys = array();
				foreach($usernames as $user) {
					$keyPath = "/home/$user/.ssh/authorized_keys";
					if(file_exists($keyPath)) {
						$keys = trim(shell_exec("sudo cat $keyPath"));
						$sshKeys[$user] = explode(PHP_EOL, $keys);
					}
				}
				return $sshKeys;
    		break;
    		default:
    			return $token;
    		break;
    	}
    }
}
?>
