<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   
    <style>
        body {
            background-image: url("assets/Images/backGround.webp");
            background-size: cover;
        }
        .container-blur {
            border-radius: 10%;
            padding: 10px;
            width: 30%;
            backdrop-filter: blur(1.5rem); /* Usar backdrop-filter en lugar de filter */
        }
        .form-container {
            padding: 20px; /* Agrega un relleno al formulario */
        }
        @media (max-width: 800px) {
            .container-blur {
                width: 80%;
            }
        }
    </style>
</head>
<body class="text-white d-flex justify-content-center align-items-center" style="height: 100vh; margin: 0">

<div class="container-blur">
    <div class="form-container">
        <form action="Controllers/loginController.php" method="POST">
            <div class="form-group text-center">
                <img src="assets/Images/userLog.png" alt="">
            </div>
            <div class="form-group">
                <label for="user">Usuario:</label><br>
                <input class="form-control" type="text" id="user" name="user"><br>
            </div>
            <div class="form-group">
                <label for="password">Password:</label><br>
                <input class="form-control" type="password" id="password" name="password"><br><br>
            </div>
            <div class="text-center">
                <input class="btn btn-primary" type="submit" id="submit" value="Iniciar sesión">
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="JavaScript/sweetAlert.js"></script>
</body>
</html>