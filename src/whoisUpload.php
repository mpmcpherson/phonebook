<?php
require 'defaultConnector.php';

$target = ["/media/michaelmcpherson/easystore/dataStore/ips"];

$files = GetFiles($target);

foreach ($files as $file) {

	$localFile = FileImport($file);
	
	//$fileVal = FileImport($localFile);
	//print_r($fileVal);
	//$fileEnd = end(explode("\/",$file));

	preg_match("/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/",$file,$fileEnd);

	foreach ($localFile as $key => $value) {
		$insert = "insert into whoisip(ip,ARINDat) values('".$fileEnd[0]."','".$value."');";
		print_r($insert."\n");
		mysqli_query($dbhandle, $insert);
		//sleep(2);
	}
}

function getData(string $ip, $whois, $dbhandle){
	print_r($ip."\n");

	$result = $whois->Lookup($ip,false);

	$stringOut = betterAbstractPrint($result['rawdata']);

	foreach ($result['rawdata'] as $key => $value) {
		 mysqli_query($dbhandle, "insert into whoisip(ip,ARINDat) values('".$ip."','".$value."');");
	}


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

//Get the data from the files on the list
function FileImport($filePathAndName)
{
	$myfile = fopen("/".$filePathAndName, "r") or die("Unable to open file!");
	$newFile = fread($myfile,filesize("/".$filePathAndName));
	fclose($myfile);

	//print_r(explode("\n",$newFile));

	$outUp = explode("\n",$newFile);

	return $outUp;

	PrintCurrentFunction(__FUNCTION__);
}

//Get the full file path of the files in the data directories.
function GetFiles($ArrayOfDataDirectories)
{	

	$outputFileList = array();

	$fileList = scandir($ArrayOfDataDirectories[0]);

	foreach($fileList as &$files)
	{
		if($files != "." && $files != "..")
		{
			array_push($outputFileList, $ArrayOfDataDirectories[0] . "/" . $files);
		}
	}
	unset($files);

	//PrintCurrentFunction(__FUNCTION__);

	return $outputFileList;
}

?>