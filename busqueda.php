<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>

<?php
session_start();

function fetch_csv_from_github() {
    $url = 'https://raw.githubusercontent.com/rvcuban/cloudComputingProject/main/job_list.csv';

    // Obtén el contenido del archivo CSV desde GitHub
    $csvContent = file_get_contents($url);

    if ($csvContent === FALSE) {
        die("Error al obtener el archivo CSV desde GitHub.");
    }

    // Escribe el contenido del CSV en un archivo local
    file_put_contents('job_list.csv', $csvContent);
}

if(isset($_GET["busqueda"])){
    echo "Estamos dentro y la búsqueda fue: ".$_GET["busqueda"];
    print "<br>";
    $busqueda = $_GET["busqueda"];
    $sql_query = "SELECT * FROM carreras WHERE NOMBRE_CARRERA = '$busqueda'";

    // Aquí puedes agregar la lógica para conectar a la base de datos y ejecutar la consulta
    // ...
} else {
    echo "Faltan datos";
}

// Llama a la función para obtener el CSV desde GitHub y actualizar el archivo local
fetch_csv_from_github();
?>

<h2>Job List from CSV</h2>

<table>
    <tr>
        <th>Job Title</th>
        <th>Company</th>
        <th>Location</th>
        <th>URL</th>
    </tr>

    <?php
    // Abre el archivo en modo lectura
    if (($handle = fopen("job_list.csv", "r")) !== FALSE) {
        // Saltar la primera línea del archivo CSV para títulos/encabezados.
        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            echo '<tr>';
            foreach ($data as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        fclose($handle);
    }
    ?>

</table>

</body>
</html>