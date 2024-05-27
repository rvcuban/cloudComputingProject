import pandas as pd
from azure.cosmos import CosmosClient, exceptions, PartitionKey

# Configura tus credenciales y cadena de conexión
endpoint = "https://jaime020302.documents.azure.com:443/"
key = "OXi6jlh4rbetCtjRkaiCqX6AnLOHYq4cfGLS2nLnjVH1oAGeiOq60q36C5Iy1JXOH4KUL4KmTApvACDbZqYu1w=="
database_name = "JobLists"
container_name = "Container1"

# Crear el cliente de Cosmos DB
client = CosmosClient(endpoint, key)
database = client.create_database_if_not_exists(id=database_name)
container = database.create_container_if_not_exists(
    id=container_name, 
    partition_key=PartitionKey(path="/id"), 
    offer_throughput=400
)

# Cargar el CSV
csv_file_path = "job_list.csv"
df = pd.read_csv(csv_file_path)

# Insertar cada fila en Cosmos DB
for index, row in df.iterrows():
    try:
        container.create_item(body=row.to_dict())
    except exceptions.CosmosHttpResponseError as e:
        print(f"Error al insertar el elemento en la fila {index}: {e}")

print("Datos importados exitosamente.")
