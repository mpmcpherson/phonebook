<?php
include 'phpWhois/src/whois.main.php';

$whois = new Whois();

$quad1 = 23;
$quad2 = 83;
$quad3 = 64;
$quad4 = 2;

while($quad1<=255){
	
	
	$res = getData($quad1.".".$quad2.".".$quad3.".".$quad4, $whois);
	if($res[0]!=255&&$res[0]!=''){
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

	$stringOut = betterAbstractPrint($result['rawdata']);
	file_put_contents("/media/michaelmcpherson/easystore/dataStore/".$ip, $stringOut);

	$rightLine;

	foreach ($result['rawdata'] as $key => $value) {
		if(preg_match("/ + [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+ - [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/",$value,$rightLine)){
			print_r($value);
			break;
		}
	}
	
	print_r("rightline ".$rightLine[0]);

	preg_match("/- [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/",$rightLine[0],$nextItem);
	
	print_r("nextitem ".$nextItem);

	$res = trim($nextItem[0]);
	print_r($res."\n");
	//print_r($result);
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