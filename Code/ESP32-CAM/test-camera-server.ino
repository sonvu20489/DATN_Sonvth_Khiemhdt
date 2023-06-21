#include "MQTT.h"
#include "esp_camera.h"
#include <FS.h>
#include <driver/gpio.h>
#include <WiFi.h>
#include <WiFiManager.h>
#include <ArduinoJson.h>
#include <SPIFFS.h>
#include "esp_timer.h"
#include "img_converters.h"

#include "Arduino.h"
#include "fb_gfx.h"
#include "soc/soc.h" //disable brownout problems
#include "soc/rtc_cntl_reg.h"  //disable brownout problems
#include "esp_http_server.h"
#include "app_httpd1.h"
#include <pgmspace.h>


#define BTN_PIN   2
#define FLASH_PIN 4
#define LED_BUILTIN_PIN 33
char email[100];
bool shouldSaveConfig = false;
const char* configPath = "/config.json";
bool Photo = false;

WiFiManager wifiManager;

#define PART_BOUNDARY "123456789000000000000987654321"

#define CAMERA_MODEL_AI_THINKER
#include "esp32cam_pin.h"
void setup() {
  WRITE_PERI_REG(RTC_CNTL_BROWN_OUT_REG, 0); //disable brownout detector
  /*setting IO port*/
  Serial.begin(115200);
  Serial.setRxBufferSize(1024);
  pinMode(LED_BUILTIN_PIN, OUTPUT);
  digitalWrite(LED_BUILTIN_PIN, LOW);
  initSPIFFS();
  pinMode(BTN_PIN,INPUT);
  pinMode(FLASH_PIN,OUTPUT);
  digitalWrite(FLASH_PIN,LOW);
  Serial.setDebugOutput(true);
  /*setting to init camera*/
  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG; 
  
  if(psramFound()){
    config.frame_size = FRAMESIZE_SVGA;
    config.jpeg_quality = 10;
    config.fb_count = 2;
  } else {
    config.frame_size = FRAMESIZE_CIF;
    config.jpeg_quality = 12;
    config.fb_count = 1;
  }
  
  /* Camera init*/
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    Serial.printf("Camera init failed with error 0x%x", err);
    return;
  }
  delay(500);
  if(digitalRead(BTN_PIN) == LOW)
  {
    Serial.println("This will reset your WiFi Config");
    Serial.println("You have 5s to Cancel");
    Serial.println("Unpress to Cancel");
    bool buttonState = monitorWipeButton(5000);
    if (buttonState == true && digitalRead(BTN_PIN) == LOW)
    {
      wifiManager.resetSettings();
      Serial.println("Reset Done");
      ESP.restart(); 
    }
    else
    {
      Serial.println("Reset Config Cancel");
    }
  }
  /*WiFi Connection*/
  WiFiManagerParameter custom_email("email","your rx email", email,100);
  //set config save notify callback
  wifiManager.setSaveConfigCallback(saveConfigCallback);
  wifiManager.addParameter(&custom_email);
  std::vector<const char *> menu = {"wifi","info","param","sep","restart","exit"};
  wifiManager.setMenu(menu);
  wifiManager.setClass("invert");
  // fetches ssid and pass from eeprom and tries to connect
  // if it does not connect it starts an access point with the specified name
  // here  "AutoConnectAP"
  // and goes into a blocking loop awaiting configuration
  bool res;
  res = wifiManager.autoConnect("ESP32-CAM Config","88888888");
  /*save email reicive warning to variable email*/
  strcpy(email, custom_email.getValue());
  /*save config to file from SPIFF file*/
  if(shouldSaveConfig) 
  {
    Serial.println("Saving config");
    DynamicJsonDocument doc(200);
    doc["email"] = email;
    File configFile = SPIFFS.open(configPath,FILE_WRITE);
    if(!configFile)
    {
      Serial.println("Failed to open config file for writing");
    }
    serializeJson(doc,configFile);
    configFile.close();
  }
  /*connect successfully -> blink flash and show the connected IP*/
  if(res)
  {
    digitalWrite(FLASH_PIN,HIGH);
    delay(500);
    digitalWrite(FLASH_PIN,LOW);
    delay(500);
    digitalWrite(FLASH_PIN,HIGH);
    delay(500);
    digitalWrite(FLASH_PIN,LOW);
    delay(500);
    Serial.print("Camera Stream Ready! Go to: http://");
    Serial.println(WiFi.localIP());
    update_url_stream(IpAddress2String(WiFi.localIP()));
    Serial.println();
    
  }
  else
  {
     Serial.println("Failed to connect or hit timeout");
  }
  /*setting callback MQTT subscriber*/
  feed2.setCallback(callback);
  feed3.setCallback(callback2);
  feed4.setCallback(callback3);
  feed5.setCallback(callback3);
  /*setting feed subscribe*/
  mqtt.subscribe(&feed5);
  mqtt.subscribe(&feed4);
  mqtt.subscribe(&feed3);
  mqtt.subscribe(&feed2);
  // Start streaming web server
  startCameraServer();
  Serial.println("{\"InitWiFi\":\"1\"}");
  /*print heap remain heap memory*/
}

void loop() {
  
  ReadJson();
  MQTT_connect();
  mqtt.processPackets(2000);
  if(! mqtt.ping()) {
    mqtt.disconnect();
  }

  
}
///////////////////////////////////////////////////
void saveConfigCallback(){
  Serial.println("Should save config");
  shouldSaveConfig = true;
}
void initSPIFFS() {
  Serial.println("mounting FS...");
  if(SPIFFS.begin())
  {
    Serial.println("Mounted file system");
    if(SPIFFS.exists(configPath))
    {
      Serial.println("reading config file");
      File configFile = SPIFFS.open(configPath);
      if(configFile)
      {
        Serial.println("opened config file");
        size_t size = configFile.size();
        // Allocate a buffer to store contents of the file.
        std::unique_ptr<char[]> buf(new char[size]);
        configFile.readBytes(buf.get(), size);
        DynamicJsonDocument doc(200);
        DeserializationError Error = deserializeJson(doc,buf.get());
        serializeJson(doc, Serial);
        if(Error)
        {
          Serial.println("Failed load json config");
          return ;
        }
        else
        {
          Serial.println("\nParsed json");
          strcpy(email,doc["email"]);
          Serial.println(email);
        }
      }
    }
    else
    {
      Serial.println("File not Found!");
    }
  }
  else
  {
    Serial.println("Failed to mount file FS");
    //end read
  }
}
///////////////////////////////////////////////////////////////
bool monitorWipeButton(uint32_t interval) {
  unsigned long currentMillis = millis(); // grab current time
  while (millis() - currentMillis < interval)  {
    int timeSpent = (millis() - currentMillis) / 1000;
    Serial.println(timeSpent);
    // check on every half a second
    if (((uint32_t)millis() % 10) == 0) {
      if (digitalRead(BTN_PIN) != LOW) {
        return false;
      }
    }
  }
  return true;
}
void ReadJson()
{
  while(Serial.available()>0)
  {
    DynamicJsonDocument json(1024);
    DeserializationError error = deserializeJson(json, Serial);
    if(error)
    {
      return;
    }
    else
    {
      /*Sendphoto using POST method*/
      Photo = json["photo"];
      if(Photo == 1)
      {
        sendPhoto();
      }
      /*sending msg and time*/
      if((json["message"])&&(json["time"]))
      {
        send_message(json["message"],json["time"]);
      }
      /*sending warning to email and */
      if(json["Warning"]=="1")
      {
//        mqtt.processPackets(2000);
//          String data = "Hello from ESP32!";
//          feed1.publish(data.c_str());
//          Serial.println("Publish");

        sendPhoto(email);

        
      }
      if((json["RFID"])&&(json["pos"])&&(json["Name"]))
      {
        JsonArray RxRFID = json["RFID"];
        JsonArray Rxslot = json["pos"];
        JsonArray RxName = json["Name"];
        String sendRFID;
        String sendslot;
        String sendName;
        serializeJson(RxRFID,sendRFID);
        serializeJson(Rxslot,sendslot);
        serializeJson(RxName,sendName);
        sendReadRFID(sendRFID, sendslot, sendName);
      }
      if(json["Rst_WiFi"] == "1")
      {
        wifiManager.resetSettings();
        Serial.println("Reset Done");
        ESP.restart();
      }
      if((json["id"]) && (json["Name"]))
      {
        JsonArray RxFingerId = json["id"];
        JsonArray RxFingerName = json["Name"];
        String sendFingerId;
        String sendFingerName;
        serializeJson(RxFingerId,sendFingerId);
        serializeJson(RxFingerName,sendFingerName);
        sendReadFinger(sendFingerId, sendFingerName);
      }
      if(json["type"] && json["action"] && json["id"] && json["name"] &&json["RFID_code"])
      {
        JsonArray RFID_code = json["RFID_code"];
        String sendRFIDcode;
        serializeJson(RFID_code,sendRFIDcode);
        sendaction(json["type"],json["action"],json["id"],json["name"], sendRFIDcode);
      }
      else if(json["type"] && json["action"] && json["id"] && json["name"])
      {
        sendaction(json["type"],json["action"],json["id"],json["name"],"NULL");
      }
      if(json["DelRFID"]=="1")
      {
        sendReadRFID("None","NULL","NULL");
      }
      if(json["DelFinger"]=="1")
      {
        sendReadFinger("None","NULL");
      }
    }
  }
  
  
}
