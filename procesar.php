<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laboratorio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para limpiar los datos ingresados
function limpiarDatos($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Validación del formulario
$nombre = limpiarDatos($_POST["nombre"]);
$apellido1 = limpiarDatos($_POST["apellido1"]);
$apellido2 = limpiarDatos($_POST["apellido2"]);
$email = limpiarDatos($_POST["email"]);
$login = limpiarDatos($_POST["login"]);
$password = limpiarDatos($_POST["password"]);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "El email introducido no es válido.";
} elseif (strlen($password) < 4 || strlen($password) > 8) {
    echo "La contraseña debe tener entre 4 y 8 caracteres.";
} else {
    // Verificar si el email ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "El email introducido ya está registrado.";
    } else {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, email, login, password) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$login', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "Registro completado con éxito.";
            echo "<br><br>";
            echo "<a href='consulta.php'>Consultar registros</a>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>