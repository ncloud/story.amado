<?php
class Tools extends CI_Controller {
    function __construct() 
    {
        parent::__construct();
    }

    function upload_files()
    {
    	$dir = APPPATH.'/webroot/files/uploads/';
    	$files = array();
    	$directories = array();
    	if ($gestor = opendir($dir)) {
		    while (false !== ($entrada = readdir($gestor))) {
		        if ($entrada != "." && $entrada != "..") {
		        	$file = $dir.$entrada;
		        	if(is_dir($file)) {
			          $directory_path = $file.DIRECTORY_SEPARATOR; 
			          $directories[$entrada] = $directory_path; 
		        	} else {
		            	echo "$file\n";

		            	$to = str_replace($dir,'', $file);
		            	$files[] = array('from'=>$file, 'to'=>'uploads/'.rawurlencode($to));
		            }
		        }
		    }

		    foreach($directories as $key=>$directory) {
		    	if ($gestor = opendir($directory)) {
				    while (false !== ($entrada = readdir($gestor))) {
				        if ($entrada != "." && $entrada != "..") {
				        	$file = $directory.$entrada;
				        	if(is_dir($file)) {
				        	} else {
				            	$to = str_replace($directories,'', $file);
				            	$files[] = array('from'=>($file), 'to'=>'uploads/' . $key . '_' . rawurlencode($to));
				            }
				        }
				    }
				}
		    }

		    closedir($gestor);

		    $this->__upload_s3($files);
		}
    }


    private function __upload_s3($files) {        
        require_once APPPATH.'/vendors/AWSSDKforPHP/sdk.class.php';
        
        CFCredentials::set(array(
            'development' => array(
                'key' => $this->config->item('aws_key'),
                'secret' => $this->config->item('aws_secret'),
                'default_cache_config' => '',
                'certificate_authority' => false
            ),
            '@default' => 'development'
        ));
        
        $s3 = new AmazonS3();   
        $s3->path_style = true;
        $s3->set_hostname('s3-ap-northeast-1.amazonaws.com');
		$s3->allow_hostname_override(false);

        $output = new StdClass;
                
        // AWS upload
        $individual_filenames = array();
		
        foreach($files as $file) {				
				$individual_filenames[] = array('from'=>$file['from'], 'to'=>$file['to']);
		}
		
		if(count($individual_filenames)) {
	        $bucket_name = 's3.withstories.com';
	        foreach($individual_filenames as $file) {
	            $s3->batch()->create_object($bucket_name, $file['to'], array(
	                'fileUpload' => realpath($file['from']),
	                'acl'=> $s3::ACL_PUBLIC
	            ));
	        }
		}
		    
        $s3->batch()->send();
        
        return $output;
    }
}
?>