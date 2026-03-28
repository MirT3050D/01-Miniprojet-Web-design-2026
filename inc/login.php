<?php
include_once 'connection.php';
function verifyUserNameAlreadyExist($username, $conn)
{
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE identifiant = :username";
    $username_statement = $conn->prepare($sql);
    $username_statement->execute([
        'username' => $username
    ]);
    $user_exists = $username_statement->fetchColumn();
    if ($user_exists > 0) {
        return true;
    }
    return false;
}
function login($username, $password, $conn)

{
    $hash_test = password_hash($password, PASSWORD_DEFAULT);
    if (verifyUserNameAlreadyExist($username, $conn)) {
        $sql = "SELECT mot_de_passe FROM utilisateurs WHERE identifiant = :username";
        $password_statement = $conn->prepare($sql);
        $password_statement->execute([
            'username' => $username
        ]);
        $hash = $password_statement->fetch();
        if (!password_verify($password, $hash['mot_de_passe'])) {
            exit;
        }
        $sql = "SELECT identifiant FROM utilisateurs WHERE identifiant =:username AND mot_de_passe =:password";
        $login_statement = $conn->prepare($sql);
        $login_statement->execute([
            'username' => $username,
            'password' => $hash['mot_de_passe']
        ]);
        $user = $login_statement->fetch();
        if ($user) {
            return $user;
            exit;
        } else {
            return null;       
            exit; 
        }
    }
}
