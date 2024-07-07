<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('uploadFile'))
{
	function uploadFile($file_field, $upload_config)
	{
		$retval = array('status'=>false,
						'error' => '',
						'path' => '');
		
		$CI =& get_instance();
		$CI->load->library('upload');
		if(is_uploaded_file($_FILES[$file_field]['tmp_name'])){
			$CI->upload->initialize($upload_config);
			prepDir($upload_config['upload_path']);
			
			if (!$CI->upload->do_upload($file_field)){	//upload gagal
				//catch the upload error message 
				$retval['status'] = false;
				$retval['error'] = $CI->upload->display_errors('<span class="help-block">','</span>');
			}else{	// upload sukses
				$up_file = $CI->upload->data();
				$retval['status'] = true;
				$retval['path'] = $upload_config['upload_path'].'/'.$up_file['file_name'];
			}
		}
		else{
			$retval['status'] = true;
		}
		return $retval;
	}
}

if ( ! function_exists('delFile'))
{
	function delFile($filepath)
	{
		if(file_exists($filepath))
		{
			unlink($filepath);
		}
		return true;
	}
}

if ( ! function_exists('makeDir'))
{
	function makeDir($dirname)
	{
		if(!file_exists($dirname))
		{
			if(mkdir($dirname))
			{
				$ret = true;
			}
			else
			{
				$ret = false;
			}
		}
		else
		{
			$ret = true;
		}
		return $ret;
	}
}

if ( ! function_exists('makeFile'))
{
	function makeFile($filename, $data)
	{
		if(!file_exists($filename))
		{
			$myFile = $filename;
			$fh = fopen($myFile, 'w') or die('error writing file');
			$stringData = $data;
			fwrite($fh, $stringData);
			fclose($fh);
		}
		return true;
	}
}

if ( ! function_exists('prepDir'))
{
	function prepDir($dirpath)
	{
		$ar_dir = explode('/',$dirpath);
		$dir = "";
		for($i=0;$i<count($ar_dir);$i++)
		{
			$dir = $dir ? $dir."/".$ar_dir[$i] : $ar_dir[$i];
			$file = $dir."/index.html";
			makeDir($dir);
			makeFile($file,"<html><body bgcolor=\"#FFFFFF\"></body></html>");
		}
	}
}

if ( ! function_exists('getDir'))
{
	function getDir($filepath, $dir_separator = "/")
	{
		$j = strlen($filepath);
		for($i=1;$i<=$j;$i++)
		{
			if(substr($filepath,-$i,1)==$dir_separator)
			{
				return substr($filepath,0,-$i);
			}
		}
	}
}

if ( ! function_exists('getFileName'))
{
	function getFileName($filepath, $dir_separator = "/")
	{
		$j = strlen($filepath);
		for($i=1;$i<=$j;$i++)
		{
			if(substr($filepath,-$i,1)==$dir_separator)
			{
				return substr($filepath,-$i+1);
			}
		}
	}
}

if ( ! function_exists('getFileRaw'))
{
	function getFileRaw($filename)
	{
		$j = strlen($filename);
		for($i=1;$i<=$j;$i++)
		{
			if(substr($filename,-$i,1)==".")
			{
				return substr($filename,0,$j-$i);
			}
		}
	}
}

if ( ! function_exists('getFileExt'))
{
	function getFileExt($filename)
	{
	
		$j = strlen($filename);
		for($i=1;$i<=$j;$i++)
		{
			if(substr($filename,-$i,1)==".")
			{
				return substr($filename,-$i+1);
			}
		}
	}
}

if ( ! function_exists('fixFileName'))
{
	function fixFileName($strin)
	{
		$archar = array();
		$j = -1;
		for($i=48;$i<=57;$i++)
		{
			$j++;
			$archar[$j] = chr($i);
		}
		for($i=65;$i<=90;$i++)
		{
			$j++;
			$archar[$j] = chr($i);
		}
		for($i=97;$i<=122;$i++)
		{
			$j++;
			$archar[$j] = chr($i);
		}
		if(strlen($strin)>0)
		{
			$strout = "";
			for($i=0;$i<=strlen($strin)-1;$i++)
			{
				$char = substr($strin,$i,1);
				if(in_array($char,$archar))
				{
					$strout = $strout.$char;
				}
				else
				{
					$strout = $strout."_";
				}
			}
			return $strout;
		}
		else
		{
			return "";
		}
	}
}

if ( ! function_exists('fixExistFile'))
{
	function fixExistFile($dirpath, $filename)
	{
		$filename_ori = $filename;
		$i=0;
		while(file_exists($dirpath.'/'.$filename))
		{
			$i++;
			$filename = getFileRaw($filename_ori).'_'.$i.'.'.getFileExt($filename_ori);
		}
		return $filename;
	}
}

?>