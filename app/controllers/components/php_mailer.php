<?php
App::import('Vendor','PhpMailer', array('file'=>'PHPMailer/class.phpmailer.php'));
class PhpMailerComponent extends Object {
	
	/**
   * Send email using SMTP Auth by default.
   */
    var $smtpUserName = '';  // SMTP username
    var $smtpPassword = ''; // SMTP password
    var $smtpHostNames= "";  // specify main and backup server
    var $smtpPort = '25';
    
    var $cc = array();
    var $bcc = array();
    var $attachments = array();
    var $subject = '';
    var $from         = '';
    var $fromName     = '';
    var $to = array();
    var $toName = array();   
    var $htmlBody = '';
    var $textBody = ''; 

   function sendSMTP() {
   		try {
		    $mail = new PHPMailer();
		    
		    $mail->__construct(true); 
			$mail->CharSet  = 'UTF-8';
		    $mail->WordWrap = 50;  // set word wrap to 50 characters
		    
		    $mail->IsSMTP();            // set mailer to use SMTP
		    $mail->SMTPAuth = true;     // turn on SMTP authentication
		    $mail->Port = $this->smtpPort;
		    $mail->Host   = $this->smtpHostNames;
		    $mail->Username = $this->smtpUserName;
		    $mail->Password = $this->smtpPassword;
		
		    $mail->From     = $this->from;
		    $mail->FromName = $this->fromName;
		    
		    foreach ($this->to as $key => $value) {
		    	$mail->AddAddress($value, $this->toName[$key] );
		    }
		
		    if (!empty($this->attachments)) {
		      foreach ($this->attachments as $attachment) {
		        if (empty($attachment['asfile'])) {
		          $mail->AddAttachment($attachment['filename']);
		        } else {
		          $mail->AddAttachment($attachment['filename'], $attachment['asfile']);
		        }
		      }
		    }
		    
		    $mail->Subject = $this->subject;
		    if (!empty($this->htmlBody)) {
		    	$mail->IsHTML(true);
		    	$mail->Body    = $this->htmlBody;  		
		    } else {
		    	$mail->Body    = $this->textBody;  		
		    }
		
		    $result = $mail->Send();
		    
   		}catch (Exception $e) { 
	      return $e->getMessage();
    	}
    	
    	return true;
    }
    
    
} 

?>