<?php
/*
	Commands:
		.bashquote [number]
*/
class bashquote_plugin{

	public function __construct(&$irc){	
		$irc->addActionHandler($this, 'quoteToChannel', '/^\.bashquote(?: (\d+))?/s');	
	}
	
	public function pluginHelp(){
		return array('bashquote', ' [number]: Shows quotes from bash.org.', true);
	}

	public function quoteToChannel(&$irc,$msg,$channel,$matches,$who) 
	{
		$page = file_get_contents('http://bash.org/?random1');
		
		preg_match_all('@<p class="qt">(.*?)</p>@s', $page, $quotes);
		if (!isset($matches[1])) {
		    $matches[1] = 1;
		} elseif ($matches[1]>50) {
		    $matches[1] = 50;
		}
		
		$primero = true;
		for ($i=0; $i < $matches[1]; $i++) {
		    if ($primero) {
			$primero = false;
		    } else {
			$irc->sayToChannel(' ', $channel);
		    }
		    
		    $irc->sayToChannel(str_replace('<br />', '', html_entity_decode($quotes[1][$i], ENT_QUOTES)), $channel);
		}
	}
}
