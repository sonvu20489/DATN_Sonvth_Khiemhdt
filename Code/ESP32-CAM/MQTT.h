#include "GET_POSTRQ.h"
#include <WiFi.h>
#include <Adafruit_MQTT.h>
#include <Adafruit_MQTT_Client.h>

#define AIO_SERVER      "io.adafruit.com"
#define AIO_SERVERPORT  1883
#define AIO_USERNAME    "sonvu20489"
#define AIO_KEY         "aio_VhLN18vddqkIv91TQWANXo6Gsx8b"
WiFiClient client2;
Adafruit_MQTT_Client mqtt(&client2, AIO_SERVER, AIO_SERVERPORT, AIO_USERNAME, AIO_KEY);


// Define Adafruit IO MQTT feeds
//String Namef_PushNotify = String(AIO_USERNAME) + "/feeds/" + apikey + ".pushnotifywarning";
String Namef_DelFinger = String(AIO_USERNAME) + "/feeds/" + apikey + ".delfinger";
String Namef_DelRFID = String(AIO_USERNAME) + "/feeds/" + apikey + ".delrfid";
String Namef_chNameFinger = String(AIO_USERNAME) + "/feeds/" + apikey + ".chnamefinger";
String Namef_chNameRFID = String(AIO_USERNAME) + "/feeds/" + apikey + ".chnamerfid";
const char * hello = "/feeds/test.test1";
//Adafruit_MQTT_Publish feed1 = Adafruit_MQTT_Publish(&mqtt, Namef_PushNotify.c_str());
Adafruit_MQTT_Subscribe feed2 = Adafruit_MQTT_Subscribe(&mqtt,  Namef_DelFinger.c_str());
Adafruit_MQTT_Subscribe feed3 = Adafruit_MQTT_Subscribe(&mqtt,  Namef_DelRFID.c_str());
Adafruit_MQTT_Subscribe feed4 = Adafruit_MQTT_Subscribe(&mqtt,  Namef_chNameFinger.c_str());
Adafruit_MQTT_Subscribe feed5 = Adafruit_MQTT_Subscribe(&mqtt,  Namef_chNameRFID.c_str());

bool connected = false; // Track MQTT connection status

void reconnect() {
  while (!mqtt.connected()) {
    Serial.println("Connecting to Adafruit IO MQTT...");
    if (mqtt.connect()==0) {
      connected = true;
      Serial.println("Connected to Adafruit IO MQTT");
      mqtt.subscribe(&feed2);
      mqtt.subscribe(&feed3);
      mqtt.subscribe(&feed4);
      mqtt.subscribe(&feed5);

    } else {
      connected = false;
      Serial.println("Failed to connect to Adafruit IO MQTT");
      delay(2000);
    }
  }
}

void callback(char *data, uint16_t len) {
  // Handle received messages for subscribed feed2

  // Convert payload to string
  String message;
  for (int i = 0; i < len; i++) {
    message += (char)data[i];
  }
  String payload = "{\"Del_FingerID\":\""+ message + "\"}";
  Serial.println(payload);
}
void callback2(char *data, uint16_t len) {
  // Handle received messages for subscribed feed2

  // Convert payload to string
  String message;
  for (int i = 0; i < len; i++) {
    message += (char)data[i];
  }
  String payload = "{\"Del_RFID\":\""+ message + "\"}";
  Serial.println(payload);
}
void callback3(char *data, uint16_t len) {
  // Handle received messages for subscribed feed2

  // Convert payload to string
  String message;
  for (int i = 0; i < len; i++) {
    message += (char)data[i];
  }
  Serial.println(message);
}
void MQTT_connect() {
  int8_t ret;

  // Stop if already connected.
  if (mqtt.connected()) {
    return;
  }

  Serial.print("Connecting to MQTT... ");

  uint8_t retries = 3;
  while ((ret = mqtt.connect()) != 0) { // connect will return 0 for connected
       Serial.println(mqtt.connectErrorString(ret));
       Serial.println("Retrying MQTT connection in 10 seconds...");
       mqtt.disconnect();
       delay(10000);  // wait 10 seconds
       retries--;
       if (retries == 0) {
         // basically die and wait for WDT to reset me
         while (1);
       }
  }
  Serial.println("MQTT Connected!");
}
