<?php
define('HOST', 'localhost');
define('USUARIO', 'root');
define('SENHA', '');
define('DB', 'criciuma_servicos');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar');

$query = "SELECT id, name, tags FROM services";
$resultado = mysqli_query($conexao, $query);

$cards = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>