<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../password.php');
    if ($_POST['action'] == 'change') {
        $sth = $db->prepare("SELECT * FROM person_login where id = ?");
        $sth->execute([$_POST['id']]);
        $log_pass = $sth->fetchAll();
        session_start();
        $_SESSION['login'] = $log_pass[0]['login_u'];
        $_SESSION['uid'] = $_POST['id'];
        header('Location: index.php');
    }
    elseif ($_POST['action'] == 'delete') {
        try {
            $id = $_POST['id'];
            include('DeleteLangsUserAction.php');
            $stmt = $db->prepare("DELETE FROM Person where id = ?");
            $stmt->execute([$id]);
            $stmt = $db->prepare("DELETE FROM person_login where id = ?");
            $stmt->execute([$id]);
            include('SelectDataUsers.php');
            $countId = count($users);
            $indexU = 0;
            for ($i = 1; $i <= $countId; $i++) {
                $tempU = intval($users[$indexU]['id']);
                $stmt = $db->prepare("UPDATE Person SET id = ? where id = $tempU");
                $stmt->execute([$i]);
                $stmt = $db->prepare("UPDATE person_login SET id = ? where id = $tempU");
                $stmt->execute([$i]);
                $stmt = $db->prepare("UPDATE person_lang SET id_u = ? where id_u = $tempU");
                $stmt->execute([$i]);
                $indexU++;
            }
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
        setcookie('save', '1');
        header('Location: admin.php');
    }
}