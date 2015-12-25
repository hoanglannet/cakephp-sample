<?php
	require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
	Zend_Loader::loadClass('Zend_Gdata_YouTube');

class ZendYoutube{
		
	function clientLogin($username, $password, $source) {
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
		
		$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
		$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
              $username,
              $password,
              'youtube',
              null,
              $source, // a short string identifying your application
              null,
              null,
              $authenticationURL
       );
		
		return 	$httpClient;	
	}
	
	function browserBasedUpload($username, $password, $source, $title, $des = '', $cate = 'Entertainment') {
		// Note that this example creates an unversioned service object.
		// You do not need to specify a version number to upload content
		// since the upload behavior is the same for all API versions.
		$httpClient = $this->clientLogin($username, $password, $source);
		$yt = new Zend_Gdata_YouTube($httpClient);
		
		// create a new VideoEntry object
		$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
		
		$myVideoEntry->setVideoTitle($title);
		$myVideoEntry->setVideoDescription($des);
		// The category must be a valid YouTube category!
		$myVideoEntry->setVideoCategory($cate);
		
		// Set keywords. Please note that this must be a comma-separated string
		// and that individual keywords cannot contain whitespace
		$myVideoEntry->SetVideoTags('cars, funny');
		
		$tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
		try {
	 		$tokenArray = $yt->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);
	 		return $tokenArray;	
		} catch (Zend_Gdata_App_HttpException $httpException) {
		  echo $httpException->getRawResponseBody();
		} catch (Zend_Gdata_App_Exception $e) {
		    echo $e->getMessage();
		}
	}
	
	function delete($username, $password, $source, $videoId) {
		$httpClient = $this->clientLogin($username, $password, $source);
		$httpClient->setHeaders('X-GData-Key', "key=${source}"); 
		$yt = new Zend_Gdata_YouTube($httpClient);
		
		try {
			$videoEntryToDelete = $yt->getVideoEntry($videoId, null, true);
			
	 		$result = $yt->delete($videoEntryToDelete);
			
	 		return $result;	
		} catch (Zend_Gdata_App_HttpException $httpException) {
		  echo $httpException->getRawResponseBody();
		} catch (Zend_Gdata_App_Exception $e) {
		    echo $e->getMessage();
		}
	}
}
?>