<?php

header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
if (!empty($_COOKIE[session_name()]) && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {
    // Если есть логин в сессии, то пользователь уже авторизован.
    ?>
      <section>
        <form action="" method="post">
          <div>Пользователь уже авторизован</div><br>
          <input class="finalBut" type="submit" name="logout" value="Выход"/>
        </form>
      </section>
    <?php
    if (isset($_POST['logout'])) {
      session_destroy();
      header('Location: ./');
      exit();
    }
    
  }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  include('../password.php');
  // Выдать сообщение об ошибках.
  $login = $_POST['login'];
  $pass = md5($_POST['pass']);
  $sth = $db->prepare("SELECT*FROM person_login");
  $sth->execute();
  $log_pass = $sth->fetchAll();
  $error_logpas = true;
  foreach($log_pass as $lp){
    if($login == $lp['login_u'] && $pass == $lp['pass_u']){
      $error_logpas = false;
      break;
    }
  }
  if($error_logpas == true){
    print('<div> Ошибка пользователя с таким логином или паролем нет </div>');
  }else{
  if (!$session_started) {
    print('<div> Всё в порядке </div>');
    session_start();
  }
}
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = count($log_pass);

  // Делаем перенаправление.
  header('Location: ./');
}
