<?php
$host="localhost"; //aqui va el localhost de mysql mor
$usuario="root"; //no se como lo tengas maria so me imagino que es solo root
$clave=""; //me imagino que no tiene clave xd
$basededatos=""; //no se como le puso ud maria asi que entre las comillas va el nombre de la basededatos :D


$conexionbasededatos=new mysqli($host, $usuario, $clave, $basededatos);

//verificar que esa vaina si este bien (creo)
if($conexionbasededatos->connect_error){
  die("Conexion fallida: " .$conexionbasededatos->connect_error);

}

//aqui se reciben los datos del registro en el coso de html
$cedula=trim($_POST['cedula']);
$nombre =trim($_POST['nombre']);
$apellidos=trim($_POST['apellidos']);
$correo=trim($_POST['correo']);
$contrasena=$_POST['contrasena'];
$confirmocontrasena=$_POST['confirmocontrasena'];
$aceptoterminos=isset($_POST['aceptoterminos']) ? 1:0;


//mas validaciones de contraseña

if($contrasena !==$confirmocontrasena){
  die("Las contraseñas no coinciden.");
  
}
if(!$aceptoterminos){
  die("Debe aceptar los términos y condiciones.");
}

//no se si ud ya tiene esa vaina encriptada pero aqui ta
$contasenasegura=password_hash($contrasena, PASSWORD_BCRYPT);


//Ahora es la base de datos, aqui si tiene que meter la tabla de usuarios o no se como le puso
//esto si es ia, lo tiene que meter, maria porque yo no lo tengo
$sql="INSERT INTO usuarios (cedula, nombre, apellidos, correo, contrasena, aceptoterminos) VALUES (?, ?, ?, ?, ?)";


//ya esto no es ia
$stmt= $conexionbasededatos->prepare($sql);
$stmt->bind_param("ssssi", $cedula, $nombre, $apellidos, $correo, $contasenasegura, $aceptoterminos);

if ($stmt ->execute()){
  echo "Cuenta creada. Puedes <a href='login.html'>iniciar sesión</a>.";

}else{
  echo"Error al crear la cuenta: " . $stmt->error;
  
}

//cerra la conexion, tengo sueño, me tocó arreglar esta monda
//aiuda
$stmt->close();
$conexionbasededatos ->close();
?>
File: registro.html

