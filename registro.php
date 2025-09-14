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
$apellido=trim($_POST['apellidos']);
$mail=trim($_POST['mail']);
$contrasena=$_POST['contrasena'];
$confirmocontrasena=$_POST['confirmocontrasena'];
$acept_terminos_condiciones=isset($_POST['acept_terminos_condiciones']) ? 1:0;


//mas validaciones de contraseña

if($contrasena !==$confirmocontrasena){
  die("Las contraseñas no coinciden.");
  
}
if(!$acept_terminos_condiciones){
  die("Debe aceptar los términos y condiciones.");
}
//saber si la cedula ya esta registrada
$checkcedula=$conn->prepare("SELECT id FROM usuarios WHERE cedula=?");
$checkcedula ->bind_param("s", $cedula);
$checkcedula ->execute();
$checkcedula ->store_result();

if($checkcedula->num_rows > 0){
  die("La cédula ya se encuentra registrada");
}
//saber si el mail ya esta registrado
$checkmail=$conexionbasededatos->prepare("SELECT id FROM usuarios WHERE mail=?");
$checkmail->bind_param("s", $mail);
$checkmail->execute();
$checkmail->store_result();

if ($checkmail->num_rows>0){
  die("El email ya está registrado");
}


//no se si ud ya tiene esa vaina encriptada pero aqui ta
$contrasenasegura=password_hash($contrasena, PASSWORD_BCRYPT);


//Ahora es la base de datos, aqui si tiene que meter la tabla de usuarios o no se como le puso
//esto si es ia, lo tiene que meter, maria porque yo no lo tengo
$sql="INSERT INTO usuarios (cedula, nombre, apellidos, mail, contrasena, acept_terminos_condiciones) VALUES (?, ?, ?, ?, ?,?)";


//ya esto no es ia
$stmt= $conexionbasededatos->prepare($sql);
$stmt->bind_param("ssssi", $cedula, $nombre, $apellido, $mail, $contrasena, $acept_terminos_condiciones);

if ($stmt ->execute()){
  echo "Cuenta creada. Puedes <a href='login.html'>iniciar sesión</a>.";

}else{
  echo"Error: " . $stmt->error;
  
}

//cerra la conexion, tengo sueño, me tocó arreglar esta monda
//aiuda
$checkcedula->close();
$checkmail->close();
$stmt->close();
$conexionbasededatos ->close();
?>
