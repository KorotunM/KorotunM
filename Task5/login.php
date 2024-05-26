<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Генерируем токен безопасности для защиты от CSRF-атак
    $csrf_token = bin2hex(random_bytes(32));
    // Сохраняем токен в сессию
    $_SESSION['csrf_token'] = $csrf_token;
    ?>

    <form action="" method="post">
      <input name="login" />
      <input name="pass" />
      <!-- Добавляем скрытое поле с токеном безопасности -->
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
      <input type="submit" value="Войти" />
    </form>

    <?php
}
else {
    include('../password.php');
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
        // Проверяем токен безопасности
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            print('<div> Ошибка токена безопасности </div>');
        } else {
            // Если все ок, то авторизуем пользователя.
            $_SESSION['login'] = $_POST['login'];
            // Записываем ID пользователя.
            $_SESSION['uid'] = count($log_pass);

            // Делаем перенаправление.
            header('Location: ./');
        }
    }
}
?>