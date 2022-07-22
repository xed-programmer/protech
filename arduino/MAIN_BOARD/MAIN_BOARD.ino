#include <SoftwareSerial.h>
SoftwareSerial sw(3,2);

#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>

#define DHTPIN 4
#define DHTTYPE    DHT22

DHT_Unified dht(DHTPIN, DHTTYPE);

uint32_t delayMS;

// SENSORS
const int smoke1=A0;
const int smoke2=A1;
const int light=A2;
const int temp=A3;

int smoke1Val, smoke2Val, lightVal;
int tempVal;
String deviceName = "LGT Sensor";
String location = "MIS";
const String api_key = "tPmAT5Ab3j7F9";

void setup() {
  Serial.begin(115200);
  sw.begin(115200);

  pinMode(smoke1, INPUT);
  pinMode(smoke2, INPUT);
//  pinMode(light, INPUT);
//  pinMode(temp, INPUT);
  dht.begin();  
  // Print temperature sensor details.
  sensor_t sensor;
  dht.temperature().getSensor(&sensor);
 
  // Print humidity sensor details.
  dht.humidity().getSensor(&sensor);
  
  // Set delay between sensor readings based on sensor details.
  delayMS = sensor.min_delay / 1000;
}

void loop() {
    smoke1Val = analogRead(smoke1);
    smoke2Val = analogRead(smoke2);
    lightVal = analogRead(light);

      delay(delayMS);
  // Get temperature event and print its value.
  sensors_event_t event;
  dht.temperature().getEvent(&event);
  if (isnan(event.temperature)) {
    Serial.println(F("Error reading temperature!"));
    tempVal = 0;
  }
  else {
    tempVal = event.temperature;
  }
    
    // SEND DATA TO SOFTWARESERIAL
//    sw.print("api_key=");
//    sw.print(api_key);
    sw.print("&name=");
    sw.print(deviceName);
    sw.print("&loc=");
    sw.print(location);
    sw.print("&light=");
    sw.print(lightVal);
    sw.print("&smoke1=");
    sw.print(smoke1Val);
    sw.print("&smoke2=");
    sw.print(smoke2Val);
    sw.print("&temp=");
    sw.print(tempVal);
    sw.print('\n');

//    Serial.print("api_key=");
//    Serial.print(api_key);
//    Serial.print("&name=");
//    Serial.print(deviceName);
//    Serial.print("&loc=");
//    Serial.print(location);
//    Serial.print("&light=");
//    Serial.print(lightVal);
//    Serial.print("&smoke1=");
//    Serial.print(smoke1Val);
//    Serial.print("&smoke2=");
//    Serial.print(smoke2Val);
//    Serial.print("&temp=");
//    Serial.print(tempVal);
//    Serial.print('\n');
    
    delay(5000);
}
