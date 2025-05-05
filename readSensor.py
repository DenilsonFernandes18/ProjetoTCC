import serial
import mysql.connector
import time

# Conectar ao Arduino
arduino = serial.Serial('COM6', 9600)  # Substitua COM3 pela porta correta
print("Conectado na porta serial. Aguardando dados...")

# Conectar ao MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="irrigacao"
)
cursor = db.cursor()

while True:
    try:
        dados = arduino.readline().decode('utf-8').strip()
        print("Recebido da serial:", dados)
        if ";" in dados:
            temp, umid = dados.split(";")
            sql = "INSERT INTO sensores (temperatura, umidade) VALUES (%s, %s)"
            cursor.execute(sql, (temp, umid))
            db.commit()
            print(f"Inserido: {temp}°C / {umid}%")
    except Exception as e:
        print("Erro:", e)
        continue
