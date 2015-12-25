<?php
App::import('Vendor','qqFileUploader', array('file'=>'valums/server.php'));

class CommonsComponent extends Object {
	var $components = array('PhpMailer');
	
	
	function upload( $path, $filename = '', $allowedExtensions = array(), $sizeLimit = 2097152, $replaceOldFile = false) {
        //create folder if not exist
        if( !file_exists($path) ) { 
            mkdir($path, 0755, true);
        } 
        
        $uploader = new qqFileUploader( $allowedExtensions, $sizeLimit );
        $result = $uploader->handleUpload( $path, $filename, $replaceOldFile );
        
        return $result;     
    }
    
	
    public function sendMail($data) {
		$this->PhpMailer->smtpUserName = $data['setting']['username'];  // SMTP username
    	$this->PhpMailer->smtpPassword = $data['setting']['password']; // SMTP password
    	$this->PhpMailer->smtpHostNames = $data['setting']['host'];  // specify main and backup server
    	$this->PhpMailer->smtpPort = $data['setting']['port'];
		
		$this->PhpMailer->subject = $data['subject'];
		$this->PhpMailer->from = $data['from'];
		$this->PhpMailer->fromName = $data['fromName'];
		$this->PhpMailer->to = $data['to'];
		$this->PhpMailer->toName = $data['toName'];
		if (isset($data['htmlBody'])) {
			$this->PhpMailer->htmlBody = $data['htmlBody'];
		} else {
			$this->PhpMailer->textBody = $data['textBody'];
		}
		
		if (isset($data['cc'])) {
			$this->PhpMailer->cc = $data['cc'];
		}
		if (isset($data['bcc'])) {
			$this->PhpMailer->cc = $data['bcc'];
		}
		if (isset($data['attachments'])) {
			$this->PhpMailer->attachments = $data['attachments'];
		}
		
		return $this->PhpMailer->sendSMTP();
	}
    
	public function buildNotifyToAdminEmail($user, $url) {
		$htmlEmail = "";
		$htmlEmail = $htmlEmail . "New account information" . "<br>";
		$htmlEmail = $htmlEmail . "Username: " . $user['User']['username'] . "<br>";		
		$htmlEmail = $htmlEmail . "Email: " . $user['User']['email'] . "<br>";
		$htmlEmail = $htmlEmail . "Status: Disabled<br>";
		$htmlEmail = $htmlEmail . "Please enable this account at: <a href='" . $url . "/admin'>Buyer website for admin</a>.<br>";
		$htmlEmail = $htmlEmail . "<br><br><b>Buyer Support</b>";	
		return $htmlEmail;	
	}
	
	public function buildNotifyToUserEmail($user, $url) {
		$htmlEmail = "";
		$htmlEmail = $htmlEmail . "Your Buyer account information" . "<br>";
		$htmlEmail = $htmlEmail . "Username: " . $user['User']['username'] . "<br>";
		if ($user['User']['active'] == 1) {
			$htmlEmail = $htmlEmail . "Status: Enabled<br>";
			$htmlEmail = $htmlEmail . "You can login to the mkbexcited website at: <a href='" . $url . "'>Buyer website</a>.<br>";
		} else {
			$htmlEmail = $htmlEmail . "Status: Disabled<br>";
			$htmlEmail = $htmlEmail . "You cannot login to the Buyer website.<br>";
		}
		$htmlEmail = $htmlEmail . "<br><br><b>Buyer Support</b>";
		return $htmlEmail; 
	}
}

?>