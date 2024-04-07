<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

<?php
// Función para obtener un nuevo mensaje del servidor Azure Function
function obtenerNuevoMensaje() {
    // URL de tu Azure Function que proporciona el nuevo mensaje
    $function_url = 'https://jaime5210.azurewebsites.net';

    // Hacer una solicitud HTTP GET a la Function App
    $response = file_get_contents($function_url);

    // Devolver el mensaje recibido
    return $response;
}

// Obtener un nuevo mensaje
$mensaje = obtenerNuevoMensaje();

// Mostrar el mensaje con un identificador único
echo "<p id='banner-message'>$mensaje</p>";
?>





</body>
<script>
    // Función para recargar el banner cada 30 segundos
    setInterval(function() {
        // Hacer una petición HTTP a banner.php para obtener un nuevo mensaje
        fetch('banner.php')
            .then(response => response.text())
            .then(data => {
                // Actualizar el contenido del banner con el nuevo mensaje
                document.getElementById('banner-message').innerHTML = data;
            })
            .catch(error => console.error('Error al obtener el nuevo mensaje:', error));
    }, 30000); // 30 segundos
</script>
</html>



