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

#To Create Push Certificate
Please follow instruction given at http://www.apptuitions.com/generate-pem-file-for-push-notification/

#Commands to create pem file from p12 files
Development Phase:

Step 1: Create Certificate .pem from Certificate .p12  
Command: openssl pkcs12 -clcerts -nokeys -out apns-dev-cert.pem -in apns-dev-cert.p12  

Step 2: Create Key .pem from Key .p12  
Command : openssl pkcs12 -nocerts -out apns-dev-key.pem -in apns-dev-key.p12  

Step 3: Optional (If you want to remove pass phrase asked in second step)  
Command : openssl rsa -in apns-dev-key.pem -out apns-dev-key-noenc.pem  

Step 4: Now we have to merge the Key .pem and Certificate .pem to get Development .pem needed for Push Notifications in Development Phase of App  
Command : cat apns-dev-cert.pem apns-dev-key-noenc.pem > apns-dev.pem (If 3rd step is performed )  

Command : cat apns-dev-cert.pem apns-dev-key.pem > apns-dev.pem (if not)  

Step 5: Check certificate validity and connectivity to APNS  
Command: openssl s_client -connect gateway.sandbox.push.apple.com:2195 -cert apns-dev-cert.pem -key apns-dev-key.pem (If 3rd step is not performed )  
Command: openssl s_client -connect gateway.sandbox.push.apple.com:2195 -cert apns-dev-cert.pem -key apns-dev-key-noenc.pem  (If performed )  

Production Phase:  

Step 1: Create Certificate .pem from Certificate .p12    
Command: openssl pkcs12 -clcerts -nokeys -out apns-pro-cert.pem -in apns-pro-cert.p12  

Step 2: Create Key .pem from Key .p12   
Command : openssl pkcs12 -nocerts -out apns-pro-key.pem -in apns-pro-key.p12  

Step 3: Optional (If you want to remove pass phrase asked in second step)    
Command : openssl rsa -in apns-pro-key.pem -out apns-pro-key-noenc.pem  

Step 4: Now we have to merge the Key .pem and Certificate .pem to get Production .pem needed for Push Notifications in Production Phase of App  
Command : cat apns-pro-cert.pem apns-pro-key-noenc.pem > apns-pro.pem (If 3rd step is performed )   
Command : cat apns-pro-cert.pem apns-pro-key.pem > apns-pro.pem (if not)  
  
Step 5: Check certificate validity and connectivity to APNS  
Command: openssl s_client -connect gateway.push.apple.com:2195 -cert apns-pro-cert.pem -key apns-pro-key.pem  (If 3rd step is not performed )  
Command: openssl s_client -connect gateway.push.apple.com:2195 -cert apns-pro-cert.pem -key apns-pro-key-noenc.pem   (If performed )  
  
This could also work.  
openssl pkcs12 -in CertificateName.p12 -out CertificateName.pem -nodes  
