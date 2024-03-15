<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty ($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print ('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include ('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['fio'])) {
  print ('Заполните имя.<br/>');
  $errors = TRUE;
}
if (empty($_POST['tel']) || !preg_match('/^\+[0-9]{11}$/', $_POST['tel'])) {
  print ('Заполните телефон.<br/>');
  $errors = TRUE;
}

if (empty ($_POST['email']) || !preg_match('/^([a-z0-9_-]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i', $_POST['email'])) {
  print ('Заполните почту.<br/>');
  $errors = TRUE;
}
if (empty ($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
  print ('Заполните год.<br/>');
  $errors = TRUE;
}

if (empty ($_POST['month'])) {
  print ('Заполните месяц.<br/>');
  $errors = TRUE;
}

if (empty ($_POST['day'])) {
  print ('Заполните день.<br/>');
  $errors = TRUE;
}

if (empty ($_POST['gender'])) {
  print ('Выберите пол.<br/>');
  $errors = TRUE;
}

$user = 'u67345';
$pass = '2030923';
$db = new PDO(
  'mysql:host=localhost;dbname=u67345',
  $user,
  $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

if (empty($_POST['like-4'])) {
  print ('Выберите ЯП.<br/>');
  $errors = TRUE;
} else {
  $sth = $db->prepare("SELECT id FROM Lang");
  $sth->execute();
  $langs = $sth->fetchAll();
  foreach ($_POST['like-4'] as $lang) {
    $flag = TRue;
    foreach ($langs as $index)
      if ($index[0] == $lang) {
        $flag = false;
        break;
      }
    if ($flag == true) {
      print ('Error: no valid language');
      $errors = true;
      break;
    }
  }
}

if (empty ($_POST['bio'])) {
  print ('Расскажите о себе.<br/>');
  $errors = TRUE;
}

if (empty ($_POST['check'])) {
  print ('Подвердите согласие.<br/>');
  $errors = TRUE;
}

if ($errors) {
  exit();
}

$stmt = $db->prepare("INSERT INTO Person (fio,tel, email, bornday, gender, bio, checked) VALUES (:fio, :tel, :email,:bornday,:gender,:bio,:checked)");
$stmt->bindParam(':fio', $fio);
$stmt->bindParam(':tel', $tel);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':bornday', $bornday);
$stmt->bindparam(':gender', $gender);
$stmt->bindparam(':bio', $checked);
$stmt->bindparam(':checked', $checked);
$fio = $_POST['fio'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$bornday = $_POST['day'] . '.' . $_POST['month'] . '.' . $_POST['year'];
$gehder = $_POST['gender'];
$bio = $_POST['bio'];
$checked = $_POST['check'];
$stmt->execute();
$id_u = lastInsertId();

foreach ($_POST['like-4'] as $lang) {
  $stmt = $db->prepare("INSERT INTO person_lang (id_u, id_lang,) VALUES (:idu,:id_lang)");
  $stmt->bindParam(':id_u', $id_u);
  $stmt->bindParam(':id_l', $lang);
  $lang = $_POST['like-4'];
  $stmt->execute();
}

header('Location: ?save=1');
