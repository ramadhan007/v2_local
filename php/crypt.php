<?php

if ( ! function_exists('encrypt1'))
{
	function encrypt1($strtext, $strkode)
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
			$arnumhsl[$i-1] = $arasctext[$i-1] * $arasckode[$j-1];
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
			$arnumhsl[$i-1] = $arnumhsl[$i-1] + 9;
		}
		for($i=1;$i<=$a;$i++)
		{
			$artemp[$i-1] = strtoupper(base_convert(trim(strval($arnumhsl[$i-1])),10,36));
		}
		$c = join("|",$artemp);
		return $c;
	}
}

if ( ! function_exists('Encrypt'))
{
	function Encrypt($strtext)
	{
		$a = encrypt1($strtext,'jnb');
		return $a;
	}
}

/* if ( ! function_exists('decrypt1'))
{
	function decrypt1($strtext, $strkode)
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
		$artemp = explode("|",$strtext);
		$a = count($artemp);
		for($i=1;$i<=$a;$i++)
		{
			$arnumhsl[$i-1] = intval(base_convert(strtolower($artemp[$i-1]),36,10));
		}
		$b = strlen($strkode);
		for($i=1;$i<=$b;$i++)
		{
			$arasckode[$i-1] = ord(substr($strkode,$i-1,1));
		}
		for($i=1;$i<=$a;$i++)
		{
			$arnumhsl[$i-1] = $arnumhsl[$i-1] - 9;
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
			$arasctext[$i-1] = $arnumhsl[$i-1] / $arasckode[$j-1];
		}
		$strtext = "";
		for($i=1;$i<=$a;$i++)
		{
			$c = chr($arasctext[$i-1]);
			$strtext = $strtext.$c;
		}
		return $strtext;
	}
}

if ( ! function_exists('Decrypt'))
{
	function Decrypt($strtext)
	{
		$a = decrypt1($strtext,'jnb');
		return $a;
	}
} */

?>