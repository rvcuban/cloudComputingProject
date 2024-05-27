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
include 'fetch_jobs.php';

if(isset ($_GET["busqueda"])){
    echo "Estamos dentro y la búsqueda fue: ".$_GET["busqueda"];
    print "<br>";
    $busqueda = $_GET["busqueda"];
    $sql_query = "SELECT * FROM carreras WHERE NOMBRE_CARRERA = '$busqueda'";


} else {
    echo "Faltan datos";
}
?>

<h2>Job List from CSV</h2>

<table>
    <tr>
        <th>Job Title</th>
        <th>Link</th>
        <th>ID</th>
        <th>Link Again</th>
    </tr>

    <?php
    // Open the file in read mode
    if (($handle = fopen("job_list.csv", "r")) !== FALSE) {
        // Skip the first line/row of the CSV file for titles/headers.
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