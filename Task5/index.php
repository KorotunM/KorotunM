<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages['entry'] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['tel'] = !empty($_COOKIE['tel_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['month'] = !empty($_COOKIE['month_error']);
  $errors['day'] = !empty($_COOKIE['day_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['lang'] = !empty($_COOKIE['lang_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['check'] = !empty($_COOKIE['check_error']);

 // Выдаем сообщения об ошибках.
 if ($errors['fio']) {
  // Удаляем куку, указывая время устаревания в прошлом.
  setcookie('fio_error', '', 100000);
  setcookie('fio_value','',100000);
  // Выводим сообщение.
  $messages['fio'] = '<div class="error">Заполните имя. Доступные символы: символы алфавита, пробел </div>';
}
if($errors['tel']){
  setcookie('tel_error','',100000);
  setcookie('tel_value','',100000);
  $messages['tel'] = '<div class="error"> Заполните телефон. Доступные символы: "+" и далее арабские цифры</div>';
}
if($errors['email']){
  setcookie('email_error','',100000);
  setcookie('email_value','',100000);
  $messages['email'] = '<div class="error"> Заполните почту. Доступные символы: символы алфавита, арабские цифры, "@" </div>';
}
if($errors['year']){
  setcookie('year_error','',100000);
  setcookie('year_value','',100000);
  $messages['year'] = '<div class="error"> Укажите год </div>';
}
if($errors['month']){
  setcookie('month_error','',100000);
  setcookie('month_value','',100000);
  $messages['month'] = '<div class="error"> Укажите месяц </div>';
}
if($errors['day']){
  setcookie('day_error','',100000);
  setcookie('day_value','',100000);
  $messages['day'] = '<div class="error"> Укажите день </div>';
}
if($errors['gender']){
  setcookie('gender_error','',100000);
  setcookie('gender_value','',100000);
  $messages['gender'] = '<div class="error"> Выберите гендер </div>';
}
if($errors['lang']){
  setcookie('lang_error','',100000);
  setcookie('lang_value','',100000);
  $messages['lang'] = '<div class="error"> Выберите языки программирования </div>';
}
if($errors['bio']){
  setcookie('bio_error','',100000);
  setcookie('bio_value','',100000);
  $messages['bio'] = '<div class="error"> Напишите о себе </div>';
}
if($errors['check']){
  setcookie('check_error','',100000);
  setcookie('check_value','',100000);
  $messages['check'] = '<div class="error"> Нажмите галочку </div>';
}

  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : strip_tags($_COOKIE['tel_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['year'] = empty($_COOKIE['year_value']) ? '' : strip_tags($_COOKIE['year_value']);
  $values['month'] = empty($_COOKIE['month_value']) ? '' : strip_tags($_COOKIE['month_value']);
  $values['day'] = empty($_COOKIE['day_value']) ? '' : strip_tags($_COOKIE['day_value']);
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : strip_tags($_COOKIE['gender_value']);
  $values['lang'] = empty($_COOKIE['lang_value']) ? '' : unserialize(strip_tags($_COOKIE['lang_value']));
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['check'] = empty($_COOKIE['check_value']) ? '' : strip_tags($_COOKIE['check_value']);

  function check_lang($values, $temp)
  {
    if (!empty($values) && !empty($values['lang'])) {
      foreach($values['lang'] as $value) {
        if ($value == $temp) {
          print("selected");
        }
      }
    }
  }
  

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  $user = 'u67345';
  $pass = '2030923';
  $db = new PDO(
    'mysql:host=localhost;dbname=u67345',
    $user,
    $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
  
  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
      $uid = $_SESSION['uid'];
      $sth = $db->prepare("SELECT fio, tel, email, bornday, gender, bio, checked FROM Person 
      WHERE id = $uid");
      $sth->execute();
      $data=$sth->fetchAll();
      $values['fio']=strip_tags($data[0]['fio']);
      $values['tel']=strip_tags($data[0]['tel']);
      $values['email']=strip_tags($data[0]['email']);

      $pos1 = strpos(strip_tags($data[0]['bornday']),'.');
      $values['day']=strip_tags(intval(substr($data[0]['bornday'], 0, $pos1)));

      $pos2 = strrpos(strip_tags($data[0]['bornday']),'.');//1.28.2005
      $values['month']=strip_tags(intval(substr($data[0]['bornday'], $pos1 + 1, $pos2 - $pos1 - 1)));
      $values['year']=strip_tags(intval(substr($data[0]['bornday'], $pos2 + 1, 4)));
      
      $values['gender']=strip_tags($data[0]['gender']);
      $values['bio']=strip_tags($data[0]['bio']);
      $values['checked']=strip_tags($data[0]['checked']);

      $sth = $db->prepare("SELECT*FROM person_lang WHERE id_u = $uid");
      $sth->execute();
      $languages = $sth->fetchAll();
      foreach($languages as $lang){
        array_push($values['lang'], strip_tags($lang['id_l']));
      }
    // и заполнить переменную $values,
    // предварительно санитизовав.
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
 // Проверяем ошибки.
 $errors = FALSE;
 if (empty($_POST['fio']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['fio'])) {
   setcookie('fio_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('fio_value', $_POST['fio'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty($_POST['tel']) || !preg_match('/^\+[0-9]{11}$/', $_POST['tel'])) {
   setcookie('tel_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('tel_value', $_POST['tel'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty ($_POST['email']) || !preg_match('/^([a-z0-9_-]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', $_POST['email'])) {
   setcookie('email_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty ($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
   setcookie('year_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('year_value', $_POST['year'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty ($_POST['month'])|| !is_numeric($_POST['month']) || $_POST['month']<0 || $_POST['month']>12 || !preg_match('/^\d+$/', $_POST['month'])) {
   setcookie('month_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('month_value', $_POST['month'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty ($_POST['day'])|| !is_numeric($_POST['day']) || $_POST['day']<0 || $_POST['day']>31 || !preg_match('/^\d+$/', $_POST['day'])) {
   setcookie('day_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('day_value', $_POST['day'], time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty($_POST['gender']) || ($_POST['gender'] != 'man' && $_POST['gender'] != 'woman')) {
   setcookie('gender_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('gender_value', $_POST['gender'], time() + 12 * 30 * 24 * 60 * 60);
 
 $user = 'u67345';
 $pass = '2030923';
 $db = new PDO(
   'mysql:host=localhost;dbname=u67345',
   $user,
   $pass,
   [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
 );
 
 if (empty($_POST['lang'])) {
   setcookie('lang_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 } else {
   $sth = $db->prepare("SELECT id FROM Lang");
   $sth->execute();
   $langs = $sth->fetchAll();
   foreach ($_POST['lang'] as $lang) {
     $flag = TRue;
     foreach ($langs as $index)
       if ($index[0] == $lang) {
         $flag = false;
         break;
       }
     if ($flag == true) {
       setcookie('lang_error', '1', time() + 24 * 60 * 60);
       $errors = true;
       break;
     }
   }
 }
 setcookie('lang_value', serialize($_POST['lang']), time() + 12 * 30 * 24 * 60 * 60);
 
 if (empty($_POST['bio']) || !preg_match('/^[a-zA-Zа-яА-ЯёЁ0-9\s.,!?:;-]+$/u', $_POST['bio'])) {
   setcookie('bio_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('bio_value', $_POST['bio'], time() + 12 * 30 * 24 * 60 * 60);
 
 if ($_POST['check']!='on' || empty($_POST['check'])) {
   setcookie('check_error', '1', time() + 24 * 60 * 60);
   $errors = TRUE;
 }
 setcookie('check_value', $_POST['check'], time() + 12 * 30 * 24 * 60 * 60);

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('tel_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('month_error', '', 100000);
    setcookie('day_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('lang_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('check_error', '', 100000);
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
        $id=intval($_SESSION['uid']);
        try{
        $stmt = $db->prepare("UPDATE Person SET fio = ?, tel = ?, email = ?, bornday = ?, gender = ?, bio = ?, checked = ?");
        $stmt->execute([$_POST['fio'], $_POST['tel'], $_POST['email'], $_POST['day'] . '.' . $_POST['month'] . '.' . $_POST['year'], $_POST['gender'], $_POST['bio'], true]);

        //очищаем старые данные в таблице языков и записываемых их новыми выбранными
        $stmt = $db->prepare("DELETE FROM person_lang where id_u = $uid where id_u = ?");
        $stmt ->execute([$id]);

        $stmt = $db->prepare("INSERT INTO person_lang (id_u, id_l) VALUES (:id_u,:id_l)");
        foreach ($_POST['lang'] as $lang) {
          $stmt->bindParam(':id_u', $id_u);
          $stmt->bindParam(':id_l', $lang);
          $id_u = $uid;
          $stmt->execute();
        }
      }
      catch(PDOException $ex){
        print('Error : ' . $ex->getMessage());
        exit();
      }
    // кроме логина и пароля.
  }
  else {
    // Генерируем уникальный логин и пароль.
    $login = uniqid('lg');
    $pass = uniqid();
    // Сохраняем в Cookies.
    setcookie('login', $login, time() + 12 * 30 * 24 * 60 * 60);
    setcookie('pass', $pass, time() + 12 * 30 * 24 * 60 * 60);
    // Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    try {
      $stmt = $db->prepare("INSERT INTO Person SET fio = ?, tel = ?, email = ?, bornday = ?, gender = ?, bio = ?, checked = ?");
      $stmt->execute([$_POST['fio'], $_POST['tel'], $_POST['email'], $_POST['day'] . '.' . $_POST['month'] . '.' . $_POST['year'], $_POST['gender'], $_POST['bio'], true]);
      $id = $db->lastInsertId();
    
      $stmt = $db->prepare("INSERT INTO person_lang (id_u, id_l) VALUES (:id_u,:id_l)");
      foreach ($_POST['lang'] as $lang) {
        $stmt->bindParam(':id_u', $id_u);
        $stmt->bindParam(':id_l', $lang);
        $id_u=$id;
        $stmt->execute();
      }

      $stmt = $db->prepare("INSERT INTO person_login SET login_u = ?, pass_u = ?");
      $stmt->execute([$login, md5($pass)]);
    }
    catch(PDOException $ex){
      print('Error : ' . $ex->getMessage());
      exit();
    }
    // ...
  }
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}
