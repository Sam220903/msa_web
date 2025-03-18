<?php
// close the session
session_start();
session_destroy();
// redirect to the login page
header("Location: ../");
exit();
