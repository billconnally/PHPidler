<?php
/*
	Commands:
		.memusage [real]
*/

class memoryusage_plugin{

	public function __construct(&$irc){	
		$irc->addActionHandler($this, 'memoryUsageToChannel', '/^\.memusage( real)?/');
	}
	
	public function pluginHelp(){
		return array('memusage', ' [real]: Shows how much ram is being used. I .memusage real is used, it also counts the memory used by the php engine.', true);
	}
	
	private function byte_convert($bytes)
	{
		$symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp = 0;
		$converted_value = 0;
		if( $bytes > 0 )
		{
			$exp = floor( log($bytes)/log(1024) );
			$converted_value = ( $bytes/pow(1024,floor($exp)) );
		}
		return sprintf( '%.2f '.$symbol[$exp], $converted_value );
	}
	
	public function memoryUsageToChannel(&$irc,$msg,$channel,$matches,$who)
	{
		if ($irc->userLevels->getLevel($who) >= USER_LEVEL_ADMIN) {
			if(isset($matches[1]))
			{
				exec('ps -orss -p ' . getmypid(), $mem);
				$mem = $mem[1] * 1024;
			}else{
				$mem = memory_get_usage(); 
			}
			$memory = $this->byte_convert($mem);
			
			//say it to the world!	
			$irc->sayToChannel('I\'m using '.$memory.' of RAM to run currently', $channel);
		}
	}
}
