<?php
// Definir constantes para los roles
const ROLE_ADMIN = 'ADMIN';
const ROLE_WORKER = 'WORKER';

// Función para generar el menú de navegación
function generateNavMenu($role)
{
    $navMenu = '
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #354656">
        <div class="container">
            <a href="../Views/home.php" class="navbar-brand d-flex align-items-center mx-3 text-decoration-none">
                <h1 class="text-white m-0 ms-2">Mini Super Alex</h1>
            </a>

            <ul class="navbar-nav ms-auto mx-3 align-items-center">
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Catalogo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Views/ProductosView.php">Productos</a></li>
                        <li><a class="dropdown-item" href="../Views/SurtidosView.php">Surtidos</a></li>
                        <li><a class="dropdown-item" href="../Views/ServiciosView.php">Servicios</a></li>
                    </ul>
                </li>
    ';

    if ($role === ROLE_ADMIN) {
        $navMenu .= '
                <li class="nav-item mx-2"><a class="nav-link" href="../Views/TrabajadoresView.php">Trabajadores</a></li>
                <li class="nav-item mx-2"><a class="nav-link" href="../Views/ProveedoresView.php">Proveedores</a></li>
                <li class="nav-item mx-2"><a class="nav-link" href="../Views/ReportesView.php">Reportes</a></li>
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ventas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Views/VentasProductosView.php">Productos</a></li>
                        <li><a class="dropdown-item" href="../Views/VentasServiciosView.php">Servicios</a></li>
                    </ul>
                </li>
        ';
    }

    $navMenu .= '
                <li class="nav-item mx-2">
                    <a class="nav-link position-relative" href="../Views/VentaView.php">
                        <img src="../assets/Images/cart.svg" alt="Carrito">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">0</span>
                    </a>
                </li>
                </li>
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link dropdown-toggle p-2 user-info" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ' . htmlspecialchars($_SESSION['user']) . '
                        <img src="../' . htmlspecialchars($_SESSION['pic']) . '" alt="avatar" style="width:50px;" class="rounded-pill">
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Controllers/logoutController.php">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    ';

    return $navMenu;
}


$role = $_SESSION['role'];
if ($role === ROLE_ADMIN) {
    echo generateNavMenu(ROLE_ADMIN);
} else if ($role === ROLE_WORKER) {
    echo generateNavMenu(ROLE_WORKER);
}