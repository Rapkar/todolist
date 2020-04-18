<?php
defined('BASE_PATH') or die('NO PERMISION'); 
function logoutUser(){
    return isset($_GET['signout']) ? true : false;
}
function isLoggedIn(){
    return isset ($_SESSION['login']) ? true : false;
}
function getLoggedInUser(){
    return $_SESSION['login'] ?? null;
}
function login($email,$password)
{

    $user=getUser($email);
        if(is_null($user)){
            return false;
        }
          #check the password
         if(password_verify($password,$user->password)){
            $_SESSION['login']=$user;
            return true;
          
            // dd($password." ".$user->password);
        }
        return false;           
}
function getUser($email){
    global $pdo;
    $sql="SELECT * FROM users WHERE email=:email";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([':email'=>$email]);
    $records=$stmt->fetchAll(PDO::FETCH_OBJ);
    return $records[0] ?? null;
}
function register($userData){
#valid  of $userData here (isValidEmail,isValidUserName,isValidPassword)
global $pdo;
$pass= password_hash( $userData['password'],PASSWORD_BCRYPT);
$sql="INSERT INTO users (name,email,password) VALUES (:name,:email,:password)";
$stmt=$pdo->prepare($sql);
$stmt->execute([':name'=>$userData['username'],':email'=>$userData['email'],'password'=>$pass]);
return $stmt->rowCount() ? true : false;
}