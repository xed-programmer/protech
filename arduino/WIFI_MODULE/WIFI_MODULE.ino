#include <SoftwareSerial.h>

SoftwareSerial sw(D2,D3);

#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>


/* Set these to your desired credentials. */
//const char *ssid = "SIGNUS3";  //ENTER YOUR WIFI SETTINGS
//const char *password = "kissmuna";

const char *ssid = "CISCO";  //ENTER YOUR WIFI SETTINGS
const char *password = "NoP@ssWorD";

//Web/Server address to read/write from 
//const String serverName = "http://10.10.10.200/protech/";
const String serverName = "http://ucc-bsit.com/BSIT3A/PRTCH/";

unsigned long lastTime = 0, timerDelay = 5000;

String postData = "";
const String api_key = "tPmAT5Ab3j7F9";

//=======================================================================
//                    Power on setup
//=======================================================================

void setup() {
  delay(1000);
  Serial.begin(115200);
  sw.begin(115200);
//  pinMode(D2,INPUT);
//  pinMode(D3,OUTPUT);
  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP
}

//=======================================================================
//                    Main Program Loop
//=======================================================================
void loop() {
  HTTPClient http;    //Declare object of class HTTPClient
  WiFiClient wifiClient;
  //Post Data
      while(sw.available()){
        char payload = sw.read();
        postData.concat(payload);
        if(payload == '\n'){
          break;
        }
    }
    if(WiFi.status()==WL_CONNECTED){
      http.begin(wifiClient,serverName + "app/includes/sensorData.inc.php");              //Specify request destination
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");     //Specify content-type header
      String httpRequestData = "api_key=" + api_key + postData;
      Serial.println(httpRequestData);
      int httpCode = http.POST(httpRequestData);   //Send the request
      String response = http.getString();    //Get the response payload

      Serial.println(httpCode);   //Print HTTP return code
      Serial.println(response);    //Print request response payload
                  
      http.end();  //Close connection   
    }else{
      Serial.println("WIFI Disconnected");
    }
    postData = "";
  delay(30);  //Post Data at every 5 seconds
}
