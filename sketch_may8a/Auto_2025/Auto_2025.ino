
/*#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define ONE_WIRE_BUS 4
#define SOIL_PIN 34 // Pino analógico para sensor de umidade

LiquidCrystal_I2C lcd(0x27, 16, 2);

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

void setup() {
  Wire.begin();
  lcd.init();
  lcd.backlight();

  sensors.begin(); // Inicia sensor DS18B20

  pinMode(SOIL_PIN, INPUT); // Define pino do sensor de solo
}

void loop() {
  // Leitura do DS18B20
  sensors.requestTemperatures();
  float tempC = sensors.getTempCByIndex(0);

  // Leitura do sensor de umidade do solo
  int valorSolo = analogRead(SOIL_PIN); // 0 (molhado) a 4095 (seco no ESP32)
  int porcentagem = map(valorSolo, 4095, 0, 0, 100); // Inverte escala para % (100% = seco)

  // Exibe no LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Temp: ");
  lcd.print(tempC);
  lcd.print((char)223); // Símbolo de grau
  lcd.print("C");

  lcd.setCursor(0, 1);
  lcd.print("Humidade: ");
  lcd.print(porcentagem);
  lcd.print("%");

  delay(2000);
}
*/
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define ONE_WIRE_BUS 4
#define SOIL_PIN 34

//Dados da sua rede Wi-Fi
const char* ssid = "ZAP-Guadalupe";
const char* password = "@Guadalupe2024";
const char* api_token = "216358ab6c125bf25090a6db185c86007cfa333a305672797762f6ba37f4ad3c";


// Endereço do servidor local
const char* serverName = "http://192.168.1.232/SmartAgro/db/salvar_dados.php";

LiquidCrystal_I2C lcd(0x27, 16, 2);
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

void setup() {
  Serial.begin(115200);
  Wire.begin();
  lcd.init();
  lcd.backlight();

  sensors.begin();
  pinMode(SOIL_PIN, INPUT);

  // Conectar ao Wi-Fi
  WiFi.begin(ssid, password);
  lcd.setCursor(0, 0);
  lcd.print("Conectando...");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("WiFi conectado");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi conectado");
  delay(2000);

  // Envia "WiFi conectado" para o servidor
  if (WiFi.status() == WL_CONNECTED) {
  HTTPClient httpStatus;
  httpStatus.begin("http://192.168.1.232/SmartAgro/db/salvar_dados.php");
  httpStatus.addHeader("Content-Type", "application/x-www-form-urlencoded");
  httpStatus.POST("status=WiFi conectado");
  httpStatus.end();
  }

}

void loop() {
  // Leitura dos sensores
  sensors.requestTemperatures();
  float tempC = sensors.getTempCByIndex(0);

  int valorSolo = analogRead(SOIL_PIN); // 0 (molhado) a 4095 (seco)
  int porcentagem = map(valorSolo, 4095, 0, 0, 100);

  // Exibir no LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Temp: ");
  lcd.print(tempC);
  lcd.print((char)223);
  lcd.print("C");

  lcd.setCursor(0, 1);
  lcd.print("Humidade: ");
  lcd.print(porcentagem);
  lcd.print("%");

  // Enviar dados via HTTP POST
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Montar os dados para enviar
    String dados = "temperatura=" + String(tempC, 2) + "&umidade=" + String(porcentagem) + "&token="   + String(api_token);


    int httpResponseCode = http.POST(dados);

    if (httpResponseCode > 0) {
      String resposta = http.getString();
      Serial.println("Dados enviados: " + resposta);
    } else {
      Serial.print("Erro ao enviar: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi desconectado");

    // Envia "WiFi desconectado" para o servidor
    HTTPClient httpStatus;
    httpStatus.begin("http://192.168.1.232/SmartAgro/db/salvar_dados.php");
    httpStatus.addHeader("Content-Type", "application/x-www-form-urlencoded");
    httpStatus.POST("status=WiFi desconectado");
    httpStatus.end();

  }

  delay(2000); // Espera 2 segundos antes da próxima leitura
}




