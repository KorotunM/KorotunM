<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
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
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['month'] = empty($_COOKIE['month_value']) ? '' : $_COOKIE['month_value'];
  $values['day'] = empty($_COOKIE['day_value']) ? '' : $_COOKIE['day_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['lang'] = empty($_COOKIE['lang_value']) ? '' : $_COOKIE['lang_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['check'] = empty($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];

  function check_lang($values, $temp)
  {
    if (!empty($values) && !empty($values['languages'])) {
      foreach($values['languages'] as $value) {
        if ($value == $temp) {
          print("selected");
        }
      }
    }
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

if (empty($_POST['gender']) || $_POST['gender'] != 'man' || $_POST['gender'] != 'woman') {
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
setcookie('fio_value', serialize($_POST['lang']), time() + 12 * 30 * 24 * 60 * 60);

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
}
catch(PDOException $ex){
  print('Error : ' . $ex->getMessage());
  exit();
}

setcookie('save', '1');

header('Location: index.php');
}