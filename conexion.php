<?php
    $host = "bcbsqv4uvaqhdam2fmyh-mysql.services.clever-cloud.com";
    $user = "u4o7vfltkahynl85";
    $clave = "sgBEZxD9yycfrbzim13P";
    $bd = "aebcbsqv4uvaqhdam2fmyh";
    //$puerto="3306";
    // $URI="mysql://u4o7vfltkahynl85:sgBEZxD9yycfrbzim13P@bcbsqv4uvaqhdam2fmyh-my;
    $conexion = mysqli_connect($host,$user,$clave,$bd);
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }
    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");
    mysqli_set_charset($conexion,"utf8");
?>
