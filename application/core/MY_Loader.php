<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Loader extends CI_Loader{
    public function __construct(){
     
    }
    public function controller($path_name){
        $CI = & get_instance();
		
		$CI->load->helper('senofile');
		
		$dir_path = getDir($path_name);
		$file_name = getFileName($path_name);
        $file_path = APPPATH.'controllers/'.$dir_path."/".ucfirst($file_name).'.php';
        $object_name = strtolower($file_name);
        $class_name = ucfirst($file_name);
     
        if(file_exists($file_path)){
            require $file_path;
            $CI->$object_name = new $class_name();
        }
        else{
            show_error("Unable to load the requested controller class: ".$class_name);
        }
    }
}