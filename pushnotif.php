<?php
/*
-------------------------------------------------------------------- 
                  PSPushNotification License, version 1.0
	Author: Pawan Kumar Singh			Email: pawan4444@gmail.com
	Copyright (c)2015 Pawan Kumar Singh
--------------------------------------------------------------------

Redistribution and use of this source code for commercial or 
non-commercial purpose is permitted without any restriction
except keep this licence information unchanged. This piece of
software code comes WITHOUT ANY WARRANTY. Please check 
http://opensource.org/licenses/GPL-3.0 for detailed licence agreement.

*/

class PSPushNotification
{
	//environment option - select any one of them
 	public $production = 1;
 	public $development = 2;  


	//socket end point configuration
	private $development_socket_endpoint = 'ssl://gateway.sandbox.push.apple.com:2195';
	private $production_socket_endpoint = 'ssl://gateway.push.apple.com:2195';

	// Put your private key's passphrase here:
	private $passphrase = '';
	private $development_private_key_file_name = 'dev.pem';
	private $production_private_key_file_name = 'dist.pem';

	//chenge this environment
	private $environment = 2;		//Default environment is development
	private $private_key_file_name = '';
	private $apn_socket_end_point = '';
	
	// Put your alert message here:
	private $message = 'Some notification';
    
    function __construct() {
		//Default - Development
		$this->environment = $this->development;
		$this->private_key_file_name = $this->development_private_key_file_name;
		$this->apn_socket_end_point = $this->development_socket_endpoint;
	}
   
	function __destruct() {
	   
	}
    
    public function setupEnvironment($environment, $message)
	{			
		if($environment == $this->production){
		
			$this->environment = $environment;
			$this->private_key_file_name = $this->production_socket_endpoint;
			$this->apn_socket_end_point = $this->production_socket_endpoint;				
		}else{

			$this->environment = $environment;
			$this->private_key_file_name = $this->development_private_key_file_name;
			$this->apn_socket_end_point = $this->development_socket_endpoint;			
		}
		if(empty($message) == FALSE)
			$this->message = $message;
			
	}

	public function sendNotificationForDeviceTokenArray($deviceTokenArray)
	{
		$context = stream_context_create();
		stream_context_set_option($context, 'ssl', 'local_cert', $this->private_key_file_name);
		stream_context_set_option($context, 'ssl', 'passphrase', $this->passphrase);
		
		// Open a connection to the APNS server
		$apnSocket = stream_socket_client( $this->apn_socket_end_point, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $context);

		if (!$apnSocket)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		echo 'Connected to APNS' . PHP_EOL;
		
		foreach ($deviceTokenArray as &$deviceToken) {
		
			$msg = $this->createMessageBodyForDeviceToken($deviceToken);
			// Send it to the server
			$result = fwrite($apnSocket, $msg, strlen($msg));
		}
		
		if (!$result)
			echo 'Error! Message not delivered to APN server.' . PHP_EOL;
		else
			echo '<br>Message successfully delivered to APN server.' . PHP_EOL;

		// Close the connection to the server
		fclose($apnSocket);		
	}
	
	function createMessageBodyForDeviceToken($deviceToken)
	{
		// Create the payload body
		$body['aps'] = array(
			'alert' => $this->message,
			'sound' => 'default',
			'content-available' => 0
		);

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
		return $msg;
	}
}
?>