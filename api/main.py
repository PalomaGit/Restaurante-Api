from datetime import date, datetime
from fastapi import FastAPI, HTTPException
import os
from motor.motor_asyncio import AsyncIOMotorClient
from pydantic import BaseModel
from typing import List

class Reserva(BaseModel):
  nombre_cliente: str
  telefono: str
  fecha: date # Formato: YYYY-MM-DD
  hora: str # Formato: HH:MM
  num_personas: int
  notas: str

app = FastAPI()

# Conexión a la BBDD
MONGO_URL = os.getenv("MONGO_URL")
client = AsyncIOMotorClient(MONGO_URL)
db = client["restaurante"]

@app.get("/")
async def root():
  return {"ok": True, "Colecciones": await db.list_collection_names()}

# Endpoint para listar todas las reservas
@app.get("/reservas", response_description="Lista de reservas", response_model=List[Reserva])
async def list_reservas():
  reservas = await db["reservas"].find().to_list(100)
  return reservas

# Endpoint para crear una nueva reserva
@app.post("/reserva", response_description="Agrega una nueva reserva", response_model=Reserva)
async def create_reserva(reserva: Reserva):
  reserva_dict = reserva.dict()
  reserva_dict["fecha"] = reserva.fecha.isoformat()
  await db["reservas"].insert_one(reserva_dict)
  return reserva

# Endpoint para obtener las reservas de un cliente específico
@app.get("/reserva/{nombre_cliente}", response_description="Obtiene reservas de un cliente específico", response_model=List[Reserva])
async def find_by_cliente_reserva(nombre_cliente: str):
  reservas = await db["reservas"].find({"nombre_cliente": nombre_cliente}).to_list(100)
  if reservas:
    return reservas
  else: 
    raise HTTPException(status_code=404, detail=f"Reservas del cliente {nombre_cliente} no encontradas en la base de datos.")
  
#Endpoint para borrar una reserva específica por cliente
@app.delete("/reserva/{nombre_cliente}", response_description="Borra reservas de un cliente")
async def delete_reserva(nombre_cliente: str):
  delete_result = await db["reservas"].delete_many({"nombre_cliente": nombre_cliente})
  if delete_result.deleted_count == 0:
    raise HTTPException(status_code=404, detail=f"Reservas del cliente {nombre_cliente} no encontradas en la base de datos.")
  else:
    return {"message": f"{delete_result.deleted_count} reserva(s) borrada(s) con éxito"}
