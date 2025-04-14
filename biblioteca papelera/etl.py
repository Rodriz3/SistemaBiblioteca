import pandas as pd

# Leer el archivo CSV original
df = pd.read_csv('BDLIBROS.csv', header=None)

# Crear una lista para almacenar los datos limpios
cleaned_data = []

# Iterar sobre las filas del DataFrame original
for i in range(len(df)):  # Iterar sobre todas las filas
    if pd.notna(df.iloc[i, 0]) and df.iloc[i, 0].isdigit() and pd.notna(df.iloc[i, 1]):  # Verificar si la fila tiene un número de registro y una fecha
        try:
            # Verificar que hay suficientes filas para procesar el registro completo
            if i + 3 < len(df):
                record = {
                    'Nº': df.iloc[i, 0],
                    'FECHA': df.iloc[i, 1],
                    'AUTOR': df.iloc[i, 5],  # Autor en la misma fila
                    'TITULO': df.iloc[i+1, 5],  # Título en la fila siguiente
                    'EDITORIAL': df.iloc[i+2, 5].split(';')[0],  # Editorial en la fila dos filas abajo, antes del punto y coma
                    'ISBN': df.iloc[i+3, 5],  # ISBN en la fila tres filas abajo
                    'D': df.iloc[i+3, 6],
                    'PUCCI NELIDA': df.iloc[i+3, 7]
                }
                cleaned_data.append(record)
            else:
                print(f"Registro incompleto en la fila {i}")
        except IndexError as e:
            print(f"Error procesando la fila {i}: {e}")

# Crear un nuevo DataFrame con los datos limpios
cleaned_df = pd.DataFrame(cleaned_data)

# Guardar el DataFrame limpio en un nuevo archivo CSV
cleaned_df.to_csv('BDLIBROS_cleaned.csv', index=False)

print("Archivo CSV limpio guardado como 'BDLIBROS_cleaned.csv'")


# import pandas as pd

# # Leer el archivo CSV original
# df = pd.read_csv('BDLIBROS.csv', header=None)

# # Crear una lista para almacenar los datos limpios
# cleaned_data = []

# # Iterar sobre las filas del DataFrame original
# for i in range(len(df)):  # Iterar sobre todas las filas
#     if pd.notna(df.iloc[i, 0]) and df.iloc[i, 0].isdigit():  # Verificar si la fila tiene un número de registro como cadena de texto
#         try:
#             record = {
#                 'Nº': df.iloc[i, 0],
#                 'FECHA': df.iloc[i, 1],
#                 'AUTOR': df.iloc[i, 5],  # Autor en la misma fila
#                 'TITULO': df.iloc[i+1, 5],  # Título en la fila siguiente
#                 'EDITORIAL': df.iloc[i+2, 5],  # Editorial en la fila dos filas abajo
#                 'ISBN': df.iloc[i+3, 5],  # ISBN en la fila tres filas abajo
#                 'D': df.iloc[i+3, 6],
#                 'VEN - DON': df.iloc[i+3, 7]
#             }
#             cleaned_data.append(record)
#         except IndexError:
#             print(f"Error procesando la fila {i}: {df.iloc[i].values}")

# # Crear un nuevo DataFrame con los datos limpios
# cleaned_df = pd.DataFrame(cleaned_data)

# # Guardar el DataFrame limpio en un nuevo archivo CSV
# cleaned_df.to_csv('BDLIBROS_cleaned.csv', index=False)

# print("Archivo CSV limpio guardado como 'BDLIBROS_cleaned.csv'")