#include "WiFi.h"
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Arduino_JSON.h>

const char *ssid = "ssid_or_wifi_name";
const char *password = "password";

//This exactly url of project that actually work 
//board=1 is board id
const char *server_name = "https://url.com/esp-outputs-action.php?action=outputs_state&board=1";

const long interval = 5000;
unsigned long previousMillis = 0;

String outputState;

String httpGETRequest(const char *server_name);

void setup()
{
    Serial.begin(115200);
    WiFi.begin(ssid, password);
    Serial.println("Connecting");
    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("Connected to WiFi network with IP address : ");
    Serial.println(WiFi.localIP());
}

void loop()
{
    unsigned long currentMillis = millis();
    if (currentMillis - previousMillis >= interval)
    {
        if (WiFi.status() == WL_CONNECTED)
        {
            outputState = httpGETRequest(server_name);

            Serial.println(outputState);
            JSONVar myObject = JSON.parse(outputState);

            if (JSON.typeof(myObject) == "undefined")
            {
                Serial.println("Parsing input failed!");
                return;
            }
            Serial.print("JSON object = ");
            Serial.println(myObject);

            JSONVar keys = myObject.keys();

            for (int i = 0; i < keys.length(); i++)
            {
                JSONVar value = myObject[keys[i]];

                Serial.print("GPIO : ");
                Serial.print(keys[i]);
                Serial.print(" - Set to: ");
                Serial.println(value);

                pinMode(atoi(keys[i]), OUTPUT);
                digitalWrite(atoi(keys[i]), atoi(value));
            }

            previousMillis = currentMillis;
        }
        else
        {
            Serial.println("Wifi connected");
        }
    }
}
String httpGETRequest(const char *server_name)
{
    WiFiClientSecure *client = new WiFiClientSecure;

    client->setInsecure();
    HTTPClient http;

    http.begin(*client, server_name);

    int httpResponseCode = http.GET();

    String payload = "{}";

    if (httpResponseCode > 0)
    {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        payload = http.getString();
    }
    else
    {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
    }
    http.end();
    return payload;
}
