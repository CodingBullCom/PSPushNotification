# PSPushNotification
PhP code for APN (Apple Push notification), simple server side code.

Sample code to use class 'PSPushNotification'  

//Note: Please read device token array from databse and create an array and pass it  
//Don't forget to set the environment from development to production while deploying on real server  
$iPad = "";  
$iPhone = "";  
$deviceTokenArray = array($iPad,$iPhone);  
$psPushNotif = new PSPushNotification();  
  
//enviroment 2 is for development and 1 for production, pass the message which user will see on alert.  
$psPushNotif->setupEnvironment(2, 'Some sample push notification');  
$psPushNotif->sendNotificationForDeviceTokenArray($deviceTokenArray);  

