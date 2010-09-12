<?php
function makeline($im,$x1,$y1,$length,$angle,$color)
{
        $x2 = $x1 + sin( deg2rad($angle) ) * $length;
        $y2 = $y1 + cos( deg2rad($angle+180) ) * $length;
        imageline($im,$x1,$y1,$x2,$y2,$color);
	$coords = array("x" => $x2, "y" => $y2);
	return $coords;
}
function str2dec($str)
{
	$hex = bin2hex($str);
	$dec = hexdec($hex);
	//echo "$hex - $dec\n";
	return $dec;
}
function getrle($handle)
{
	$data = bin2hex(fread($handle,1));
	$split_data = str_split($data,1);
	$rle_data['run'] = hexdec($split_data[0]);
	$rle_data['code'] = hexdec($split_data[1]);
	return $rle_data;
}
function half($handle)
{
	return str2dec(fread($handle,2));
}
function whole($handle)
{
	return str2dec(fread($handle,4));
}
function sec2hms ($sec, $padHours = false) 
{
    $hms = "";

    $hours = intval(intval($sec) / 3600); 
    $hms .= ($padHours) 
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
          : $hours. ":";

    $minutes = intval(($sec / 60) % 60); 
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

    $seconds = intval($sec % 60); 
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    return $hms;
}
$filename = 'sn.last';
$handle = fopen($filename, "rb");
fread($handle, 30);
$header['code'] = half($handle);
$header['date'] = jdtogregorian(half($handle) + 2440586.5);
$header['time'] = sec2hms(whole($handle), true);
$header['length'] = whole($handle);
$header['source'] = half($handle);
$header['destination'] = half($handle);
$header['numberblocks'] = half($handle);
half($handle);
$proddescription['latitude'] = whole($handle) / 1000;
$proddescription['longitude'] = (-1 * whole($handle)) / 1000; // How to handle negative numbers???
$proddescription['height'] = half($handle);
$proddescription['code'] = half($handle);
$proddescription['mode'] = half($handle);
$proddescription['volumecoveragepattern'] = half($handle);
$proddescription['sequencenumber'] = half($handle);
$proddescription['scannumber'] = half($handle);
$proddescription['scandate'] = jdtogregorian(half($handle) + 2440586.5);
$proddescription['scantime'] = sec2hms(whole($handle), true);
$proddescription['generationdate'] = jdtogregorian(half($handle) + 2440586.5);
$proddescription['generationtime'] = sec2hms(whole($handle), true);
$proddescription['productspecific1'] = half($handle);
$proddescription['productspecific2'] = half($handle);
$proddescription['elevationnumber'] = half($handle);
$proddescription['productspecific3'] = half($handle) / 10; //BR Elevation Angle
$proddescription['threshold1'] = half($handle);
$proddescription['threshold2'] = half($handle);
$proddescription['threshold3'] = half($handle);
$proddescription['threshold4'] = half($handle);
$proddescription['threshold5'] = half($handle);
$proddescription['threshold6'] = half($handle);
$proddescription['threshold7'] = half($handle);
$proddescription['threshold8'] = half($handle);
$proddescription['threshold9'] = half($handle);
$proddescription['threshold10'] = half($handle);
$proddescription['threshold11'] = half($handle);
$proddescription['threshold12'] = half($handle);
$proddescription['threshold13'] = half($handle);
$proddescription['threshold14'] = half($handle);
$proddescription['threshold15'] = half($handle);
$proddescription['threshold16'] = half($handle);
$proddescription['productspecific4'] = half($handle);  // BR Max Reflectivity
$proddescription['productspecific5'] = half($handle);
$proddescription['productspecific6'] = half($handle);
$proddescription['productspecific7'] = half($handle);
$proddescription['productspecific8'] = half($handle); // BR Cal. Constant (MSB)
$proddescription['productspecific9'] = half($handle); // BR Cal. Constant (LSB)
$proddescription['productspecific10'] = half($handle);
$proddescription['version'] = half($handle);
$proddescription['symbologyoffset'] = whole($handle);
$proddescription['graphicoffset'] = whole($handle);
$proddescription['tabularoffset'] = whole($handle);
fseek($handle, (($proddescription['symbologyoffset'] * 2) + 30) );
half($handle);
$symbology['blockid'] = half($handle);
$symbology['blocklength'] = whole($handle);
$symbology['numoflayers'] = half($handle);
$symbology['layerdivider'] = half($handle);
$symbology['layerlength'] = whole($handle);
$symbology['layerpacketcode'] = half($handle);  // BR Packet Type is HEX (0xAF1F)
$symbology['layerindexoffirstrangebin'] = half($handle);
$symbology['layernumberofrangebins'] = half($handle);
$symbology['i_centerofsweep'] = half($handle);
$symbology['j_centerofsweep'] = half($handle);
$symbology['scalefactor'] = half($handle) / 1000; // Number of pixels per range bin
$symbology['numberofradials'] = half($handle);

function debprint($printme)
{
	echo "<pre>";
	print_r($printme);
	echo "</pre>";
}

$im = @ImageCreate (520, 460);
imageantialias($im, true);
imagealphablending($im, true);

$background_color = ImageColorAllocate ($im, 0, 0, 0);

$color[1] = ImageColorAllocate ($im, 153, 255, 255);
$color[2] = ImageColorAllocate ($im, 102, 153, 255);
$color[3] = ImageColorAllocate ($im, 0, 0, 204);
$color[4] = ImageColorAllocate ($im, 153, 255, 0);
$color[5] = ImageColorAllocate ($im, 51, 204, 0);
$color[6] = ImageColorAllocate ($im, 51, 102, 0);
$color[7] = ImageColorAllocate ($im, 255, 255, 51);
$color[8] = ImageColorAllocate ($im, 255, 204, 0);
$color[9] = ImageColorAllocate ($im, 255, 153, 0);
$color[10] = ImageColorAllocate ($im, 255, 0, 0);
$color[11] = ImageColorAllocate ($im, 204, 0, 0);
$color[12] = ImageColorAllocate ($im, 153, 0, 0);
$color[13] = ImageColorAllocate ($im, 255, 0, 204);
$color[14] = ImageColorAllocate ($im, 204, 0, 255);
$color[15] = ImageColorAllocate ($im, 255, 255, 255);

imagerectangle($im,       490, 0, 515, 15, 15);
imagefilledrectangle($im, 490, 20, 515, 35, 2);
imagefilledrectangle($im, 490, 40, 515, 55, 3);
imagefilledrectangle($im, 490, 60, 515, 75, 4);
imagefilledrectangle($im, 490, 80, 515, 95, 5);
imagefilledrectangle($im, 490, 100, 515, 115, 6);
imagefilledrectangle($im, 490, 120, 515, 135, 7);
imagefilledrectangle($im, 490, 140, 515, 155, 8);
imagefilledrectangle($im, 490, 160, 515, 175, 9);
imagefilledrectangle($im, 490, 180, 515, 195, 10);
imagefilledrectangle($im, 490, 200, 515, 215, 11);
imagefilledrectangle($im, 490, 220, 515, 235, 12);
imagefilledrectangle($im, 490, 240, 515, 255, 13);
imagefilledrectangle($im, 490, 260, 515, 275, 14);
imagefilledrectangle($im, 490, 280, 515, 295, 15);

for($i=1;$i<=360;$i++)
{

	$numofrle = half($handle);
	$startangle = half($handle) / 10;
	$angledelta = half($handle) / 10;
	
	$run = 0;
	// Loop through the radial data packets
	$coords = NULL;
	for($j=1;$j<=$numofrle;$j++)
	{
		if ($coords == NULL) $coords = array("x" => 230, "y" => 230);
		$RLE = getrle($handle);
                $run = $RLE['run'];
		$code = $RLE['code'];
                $coords = makeline($im,$coords['x'],$coords['y'],$run,$startangle,$code);

		$RLE = getrle($handle);
		$run = $RLE['run'];	
		$code = $RLE['code'];
		$coords = makeline($im,$coords['x'],$coords['y'],$run,$startangle,$code);

	}
}
header("Content-type: image/png");
imagepng($im); 

?>
