<?php

include( dirname(__FILE__)."/http.php");


class dtube
{
    var $devKey;
    var $source;
    var $user;
    var $pass;
    var $error;
    
    function dtube($key,$source,$user,$pass)
    {
        $this->devKey = $key;
        $this->user = $user;
        $this->pass = $pass;
        $this->source = $source;
    }
    
    function login()
    {
        $user = rawurlencode($this->user);
        $pass = rawurlencode($this->pass);
        $source = $this->source;
        $url = "https://www.google.com/youtube/accounts/ClientLogin";   
        $post = array (
            "Email"=> $user,
            "Passwd"=> $pass,
            "service"=>"youtube",
            "source"=>$source
        );
        list($header,$body) = http_adv_get("POST",$url,array("PostValues"=>$post) );
        if ( strpos( key($header),"200") === false)
        {
            $this->error = substr($body,6);
            return array(false,substr($body,6));
        }
        $body = explode("\n",$body);
        return array(true,substr($body[0],5));
    }
    
    function upload($title,$desc,$cat,$keywords)
    {
        $this->error = "";
        $key = $this->devKey;
        $auth= $this->login();
        if ( !$auth[0] ) {
        	return 'Login fail.';
        }
            
        $auth = $auth[1];
        
        $xml = <<<EOF
<?xml version="1.0"?>
<entry xmlns="http://www.w3.org/2005/Atom"
  xmlns:media="http://search.yahoo.com/mrss/"
  xmlns:yt="http://gdata.youtube.com/schemas/2007">
  <media:group>
    <media:title type="plain">$title</media:title>
    <media:description type="plain">
      $desc
    </media:description>
    <media:category scheme='http://gdata.youtube.com/schemas/2007/categories.cat'>
        $cat
    </media:category>
    <media:keywords>$keywords</media:keywords>
  </media:group>
</entry>
EOF;
        $url = "http://gdata.youtube.com/action/GetUploadToken";
        $headers=array(
            "Authorization" => 'GoogleLogin auth='.$auth,
            "X-GData-Key"   => 'key='.$key,
            "Content-length"=> strlen($xml),
            "Content-Type"  => "application/atom+xml; charset=UTF-8"
        );
        list($header,$body) = http_adv_get("POST",$url,array("Body"=>$xml),$headers);
        if ( strpos( key($header),"200") === false)
        {
            $this->error = "Unexpected response";
            return $this->error;
        }
        preg_match("/<url>(.*)<\/url>/i",$body,$url);
        preg_match("/<token>(.*)<\/token>/i",$body,$token);
        return array($url[1],$token[1]);
    }
}

function http_adv_get($qtype,$url,$extra=array(),$headers=array())
{
    $http = new http_class;
    $http->debug=0;
    $http->html_debug=0;
    $http->request_method=$qtype;
    $http->GetRequestArguments($url,$args);
    $err = $http->Open($args);
    foreach($extra as $key => $value)
    {
        $args[$key] = $value;
    }
    $body="";
    foreach($headers as $key => $value)
    {
        $args['Headers'][$key] = $value;
    }   
    if ( $err=="")
    {
        $err = $http->Open($args);
        $err = $http->SendRequest($args);
        $http->ReadReplyHeaders($headers);
        
        if ( $err=="")
        {
            if ( $err == "")
                for(;;)
                {
                    $err = $http->ReadReplyBody($acc,2000);
                    if ( $err!=""||$acc=="") break;
                    $body.=$acc;
                }
        }
        $http->close();
    }
    return array($headers,$body);
}


?>