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
require "base_datos.php";

if(isset ($_GET["busqueda"])){
    echo "estamos dentro y la busqueda fue ".$_GET["busqueda"];
    print "<br>";
    $busqueda =$_GET["busqueda"];
    $sql_query = "Select * 
                 from carreras 
                      where NOMBRE_CARRERA ='$busqueda'" ;

    $result = mysqli_query($connect, $sql_query);


    if ($result) {

        if (mysqli_num_rows($result) > 0) {
            $rec = mysqli_fetch_row($result);
            $id = $rec[0];
            $_SESSION['carrera_nombre'] = $rec[1];
            $_SESSION['campo'] = $rec[2];
            print  $_SESSION['carrera_nombre'];
            print "<br>";
           print $_SESSION['campo'];
            print "<br>";
            
    


        } else print "No records to show.";
    } else {
        print "Something went wrong!!!";
    }

    // header( "Refresh:2; url=login.html", true, 303);
} else {
    echo "faltan datos";
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



</body>
</html>



