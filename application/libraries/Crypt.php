<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crypt {
	
	private $strkode = '';
	
	public function __construct()
	{
		$this->strkode = "G(W5g?px&~uCuS6_B=";
		// $this->strkode = chr(255);
	}
	
	private function encrypt1($strtext, $strkode)
	{
		$strtext = trim($strtext);
		$strkode = trim($strkode);
		if($strtext == "")
		{
			return "";
		}
		if($strkode == "")
		{
			$strkode = "x";
		}
		$a = strlen($strtext);
		for($i=1;$i<=$a;$i++)
		{
			$arasctext[$i-1] = ord(substr($strtext,$i-1,1));
		}
		$b = strlen($strkode);
		for($i=1;$i<=$b;$i++)
		{
			$arasckode[$i-1] = ord(substr($strkode,$i-1,1));
		}
		for($i=1;$i<=$a;$i++)
		{
			$j = $i%$b;
			if($j==0)
			{
				$j = $b;
			}
			$arnumhsl[$i-1] = $arasctext[$i-1] * ($arasckode[$j-1]%10 + 1);
		}
		for($i=1;$i<=$a;$i++)
		{
			$j = $i % $b;
			if($j==0)
			{
				$j = $b;
			}
			$arnumhsl[$i-1] = $arnumhsl[$i-1] + $arasckode[$j-1];
		}
		for($i=1;$i<=$a;$i++)
		{
			$arnumhsl[$i-1] = $arnumhsl[$i-1] + 5000;
		}
		for($i=1;$i<=$a;$i++)
		{
			$artemp[$i-1] = base_convert(trim(strval($arnumhsl[$i-1])),10,36);
		}
		$c = join("",$artemp);
		return $c;
	}
	
	public function encrypt($strtext)
	{
		$a = $this->encrypt1($strtext, $this->strkode);
		return $a;
	}
	
	private function decrypt1($strtext, $strkode)
	{
		$strtext = trim($strtext);
		$strkode = trim($strkode);
		if($strtext == "")
		{
			return "";
			exit;
		}
		if($strkode == "")
		{
			$strkode = "x";
		}
		for($i=0; $i<(strlen($strtext)/3); $i++){
			$artemp[$i] = substr($strtext,$i*3,3);
		}
		$a = count($artemp);
		for($i=1;$i<=$a;$i++)
		{
			$arnumhsl[$i-1] = intval(base_convert($artemp[$i-1],36,10));
		}
		$b = strlen($strkode);
		for($i=1;$i<=$b;$i++)
		{
			$arasckode[$i-1] = ord(substr($strkode,$i-1,1));
		}
		for($i=1;$i<=$a;$i++)
		{
			$arnumhsl[$i-1] = $arnumhsl[$i-1] - 5000;
		}
		for($i=1;$i<=$a;$i++)
		{
			$j = $i%$b;
			if($j==0)
			{
				$j = $b;
			}
			$arnumhsl[$i-1] = $arnumhsl[$i-1] - $arasckode[$j-1];
		}
		for($i=1;$i<=$a;$i++)
		{
			$j = $i%$b;
			if($j==0)
			{
				$j = $b;
			}
			$arasctext[$i-1] = $arnumhsl[$i-1] / ($arasckode[$j-1]%10 + 1);
		}
		$strtext = "";
		for($i=1;$i<=$a;$i++)
		{
			$c = chr($arasctext[$i-1]);
			$strtext = $strtext.$c;
		}
		return $strtext;
	}
	
	public function decrypt($strtext)
	{
		$a = $this->decrypt1($strtext,$this->strkode);
		return $a;
	}
}
