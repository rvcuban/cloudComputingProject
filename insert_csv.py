import pandas as pd
from azure.cosmos import CosmosClient, exceptions, PartitionKey

# Configura tus credenciales y cadena de conexión
endpoint = "https://jaime020302.documents.azure.com:443/"
key = "OXi6jlh4rbetCtjRkaiCqX6AnLOHYq4cfGLS2nLnjVH1oAGeiOq60q36C5Iy1JXOH4KUL4KmTApvACDbZqYu1w=="
database_name = "JobLists"
container_name = "ContainerJobs"

# Crear el cliente de Cosmos DB
client = CosmosClient(endpoint, key)
database = client.create_database_if_not_exists(id=database_name)
container = database.create_container_if_not_exists(
    id=container_name, 
    partition_key=PartitionKey(path="/ID"), 
    offer_throughput=400
)

# Cargar el CSV
file_path = 'job_list.csv'

# Leer y limpiar el CSV
try:
    df = pd.read_csv(file_path, delimiter=',', on_bad_lines='skip')
    print("CSV cargado exitosamente")
except pd.errors.ParserError as e:
    print(f"Error al analizar el archivo CSV: {e}")
    exit()

# Mostrar las primeras filas del DataFrame para depuración
print("Primeras filas del DataFrame:")
print(df.head())

# Verificar que las filas tengan el mismo número de columnas
column_count = df.shape[1]

# Función para validar datos
def validar_datos(fila):
    # Asegúrate de que la fila tenga la clave de partición y cualquier otro campo necesario
    if 'ID' not in fila or not fila['ID']:
        return False
    # Puedes agregar más validaciones según tus necesidades
    return True

# Insertar cada fila en Cosmos DB
for index, row in df.iterrows():
    if len(row) == column_count and validar_datos(row):  # Solo insertar filas válidas
        item = row.to_dict()
        print(f"Insertando elemento en la fila {index}: {item}")
        try:
            container.create_item(body=item)
        except exceptions.CosmosHttpResponseError as e:
            print(f"Error al insertar el elemento en la fila {index}: {e}")
    else:
        print(f"Fila {index} omitida debido a número incorrecto de columnas o datos inválidos.")

print("Proceso de importación completado.")
