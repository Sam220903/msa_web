<?php
$user = $_POST['user'];
$password = md5($_POST['password']);
include '../Repository/queries.php';
include '../Libraries/linkStyles.php';
session_start();

$result = getLogin($user, $password);
$rows = mysqli_fetch_array($result);

// Verificar si el usuario existe
if ($rows) {
    // El usuario existe
    $_SESSION['user'] = strtoupper($user);
    $_SESSION['password'] = $password;
    $_SESSION['id'] = $rows['ID'];
    $role = getRole($user);
    if ($role) {
        $roleS = mysqli_fetch_array($role);
        $_SESSION['role'] = $roleS['rol'];
    }
    $pic = getPicTrabajador($password);
    if ($pic) {
        $picS = mysqli_fetch_array($pic);
        $_SESSION['pic'] = $picS['Foto'];
    }

    // Verificar el rol
    if ($rows['id_rol'] == 1) {
        echo "<script>
        setTimeout(function() {
            window.location.href = '../Views/home.php';
        }, 1000); // Redirige después de 1 segundo (ajusta este valor según sea necesario)
        </script>";
    
        // Muestra la alerta después de la redirección
        echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Inicio de sesión administrador',
                  text: '¡Bienvenido Administrador!',
                  icon: 'success',
                showConfirmButton: false,
            });
        }, 1); // Muestra la alerta después de medio segundo (ajusta este valor según sea necesario)
        </script>";
    }
     else if ($rows['id_rol'] == 2) {
        echo "<script>
        setTimeout(function() {
            window.location.href = '../Views/home.php';
        }, 1000); // Redirige después de 1 segundo (ajusta este valor según sea necesario)
        </script>";
    
        // Muestra la alerta después de la redirección
        echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Inicio de sesión trabajador',
                  text: '¡Bienvenido Trabajador!',
                  icon: 'success',
                showConfirmButton: false,
            });
        }, 1); // Muestra la alerta después de medio segundo (ajusta este valor según sea necesario)
        </script>";
    }
} else {
    // El usuario no existe, redirigir a index.php
    
    echo "<script>
    setTimeout(function() {
        window.location.href = '../';
    }, 1000); // Redirige después de 1 segundo (ajusta este valor según sea necesario)
    </script>";

    // Muestra la alerta después de la redirección
    echo "<script>
    setTimeout(function() {
        Swal.fire({
            title: '¡Error al iniciar sesión!',
            text: 'Datos incorrectos.',
            icon: 'error',
            showConfirmButton: false,
        });
    }, 1); // Muestra la alerta después de medio segundo (ajusta este valor según sea necesario)
    </script>";
    exit();
}

mysqli_free_result($result);