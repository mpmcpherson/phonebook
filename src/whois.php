<?php
include 'phpWhois/src/whois.main.php';

$whois = new Whois();

$quad1 = 0;
$quad2 = 0;
$quad3 = 0;
$quad4 = 0;

while($quad1<=255){
	
	
	$res = getData($quad1.".".$quad2.".".$quad3.".".$quad4, $whois);
	if($res[0]!=255){
		$quad1 = $res[0];
		$quad2 = $res[1];
		$quad3 = $res[2];
		$quad4 = $res[3];
	}
	
	$ip = $quad1.".".$quad2.".".$quad3.".".$quad4; 	

	sleep(1);

	if($quad4<255){
		$quad4++;
	}else{
		//quad4 else
		$quad4 = 0;
		if($quad3<255){
			$quad3++;
		}else{
			//quad3 else
			$quad3 = 0;
			if($quad2<255){
				$quad2++;
			}else{
				//quad2 else
				$quad2=0;
				if($quad1<255){
					$quad1++;
				}else{
					$quad1++;//this will break the loop
					//quad1 else
					//quad1 needs no resetting

				}
			}
		}
	}
	

	
	
}

function getData(string $ip, $whois){
	print_r($ip."\n");

	$result = $whois->Lookup($ip,false);

	//print_r(betterAbstractPrint($result['regrinfo']));
	//print_r($result['regrinfo']);
	//print_r("\n");
	//print_r("organization ".$result['regrinfo']['owner']['organization']."\n");
	//print_r("inet number ".$result['regrinfo']['network']['inetnum']."\n");
	//print_r("name ".$result['regrinfo']['network']['name']."\n");
	//print_r("handle ".$result['regrinfo']['network']['handle']."\n");
	//print_r("status ".$result['regrinfo']['network']['status']."\n");
	//print_r("changed ".$result['regrinfo']['network']['changed']."\n");
	
	//print_r($result['regrinfo']['owner']['address']);
	//print_r("\n");
	$stringOut = betterAbstractPrint($result['regrinfo']);
	file_put_contents("/media/michaelmcpherson/easystore/dataStore/".$ip, $stringOut);

	//$nextItem;
	preg_match("/ [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/",$result['regrinfo']['network']['inetnum'],$nextItem);
	$res = trim($nextItem[0]);
	print_r($res."\n");

	preg_match_all("/[0-9]+/",$res,$matAry);
	$quad1 = $matAry[0][0]; 
	$quad2 = $matAry[0][1]; 
	$quad3 = $matAry[0][2]; 
	$quad4 = $matAry[0][3]; 

	return $matAry[0];

	
}

function betterAbstractPrint($obj){
	$print = "";
	foreach($obj as $key => $value) {
		$print .= $key.":";
		if(gettype($value) == 'array'){
			$print .= "array()\n\t" . betterAbstractPrint($value)."\n";
		}
		else{
			if($key === "clean"){
				$print .= strval($value)."\n";
			}else{
				$print .= $value."\n";
			}
		}
	}
	return $print;
}
?>