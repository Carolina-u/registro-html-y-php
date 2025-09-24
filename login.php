<?php
session_start();

//configuracion de base de datos
$servername="localhost";
$username="root";
$password="";
$dbname="simus_mjn";

//crear la conexion
$conn =new mysqli($servername, $username, $password, $dbname);

//verificar
if ($conn->connect_error){
    die ("Error en la conexion con la base de datos: ".$conn->connect_error);
}
//ver que si se envio el formulario
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //obtener datos
    $cedula=trim($_POST['cedula']);
    $contrasena=$_POST['contrasena'];

    //que no este vacio
    if (empty($cedula)|| empty($contrasena)){
        header("Location: login.html?error=camposvacios");
        exit;
    }
    //ver que la cedula solo tenga numeros
    if (!preg_match('/^\d+$/', $cedula)){
        header("Location: login.html?error=formatoCedulaInvalido");
    }
    $sql=""; //ingresar los apartados de la base de datos con FROM usuarios WHERE cedula=?
    $stmt=$conn->prepare($sql);
    if ($stmt){
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result=$stmt->get_result();

        //saber si encontro el usuario

        if ($result->num_rows===1){
            $usuario=$result->fetch_assoc();

            //contraseña
            if (password_verify($contrasena, $usuario['password_hash'])){
                $_SESSION['usuarioid'] = $usuario['id'];
                $_SESSION['usuariocedula'] = $usuario ['cedula' ];
                $_SESSION['usuarionombre'] = $usuario ['nombre'] ; 
                $_SESSION['usuariomail'] = $usuario ['email'];
                $_SESSION['loggedin'] = true;
                $_SESSION['login_time'] = time();   

                //header("Location: despuesdeliniciosesion.php") aqui va en coso de poder modificar la cuenta, elminarla, etc
                //exit;
            } else{
                //contraseña incorrecta
                header("Location: login.html?error=contrasenaIncorrecta");
                exit;
            }
        }else { //error en la preparacion de la consulta
            header("Location:login.html?error=usuarioNoEncontrado");
            exit;
        }
    }else{
        //si alguin intenta entrar al php sin el formulario
        header("Location: login.html");
        exit;
    }
$conn->close();
}
?>
