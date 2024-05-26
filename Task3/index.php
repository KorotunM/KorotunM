<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty ($_GET['save'])) {
    print (htmlspecialchars('Спасибо, результаты сохранены.'));
  }
  include ('form.php');
  exit();
}
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]{1,150}$/u', $_POST['fio'])) {
  print (htmlspecialchars('Заполните имя.<br/>'));
  $errors = TRUE;
}
if (empty($_POST['tel']) || !preg_match('/^\+[0-9]{11}$/', $_POST['tel'])) {
  print (htmlspecialchars('Заполните телефон.<br/>'));
  $errors = TRUE;
}

if (empty($_POST['fio']) || !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $_POST['fio'])) {
  $errors = TRUE;
}

// Проверяем, что телефон содержит только цифры и начинается с плюса
if (empty($_POST['tel']) || !preg_match('/^\+\d{11}$/', $_POST['tel'])) {
  $errors = TRUE;
}

// Проверяем, что почта соответствует формату email
if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  $errors = TRUE;
}

// Проверяем, что дата рождения содержит только цифры и разделена точками
if (empty($_POST['day']) || !preg_match('/^\d{1,2}$/', $_POST['day']) ||
    empty($_POST['month']) || !preg_match('/^\d{1,2}$/', $_POST['month']) ||
    empty($_POST['year']) || !preg_match('/^\d{4}$/', $_POST['year'])) {
  $errors = TRUE;
}

// Проверяем, что пол соответствует одному из двух вариантов
if (empty($_POST['gender']) || ($_POST['gender'] != 'man' && $_POST['gender'] != 'woman')) {
  $errors = TRUE;
}

// Проверяем, что биография содержит только буквы, цифры, пробелы и знаки препинания
if (empty($_POST['bio']) || !preg_match('/^[a-zA-Zа-яА-ЯёЁ0-9\s.,!?:;-]+$/u', $_POST['bio'])) {
  $errors = TRUE;
}

// Проверяем, что языки программирования соответствуют списку допустимых языков
include('../password.php');

if (empty($_POST['like-4'])) {
  $errors = TRUE;
} else {
  $sth = $db->prepare("SELECT id FROM Lang");
  $sth->execute();
  $langs = $sth->fetchAll();
  foreach ($_POST['like-4'] as $lang) {
    $flag = true;
    foreach ($langs as $index) {
      if ($index[0] == $lang) {
        $flag = false;
        break;
      }
    }
    if ($flag == true) {
      $errors = true;
      break;
    }
  }
}

// Проверяем, что пользователь согласился с условиями
if ($_POST['check']!='on' || empty($_POST['check'])) {
  $errors = TRUE;
}

if ($errors) {
  exit();
}

try {
  $stmt = $db->prepare("INSERT INTO Person SET fio = ?, tel = ?, email = ?, bornday = ?, gender = ?, bio = ?, checked = ?");
  $stmt->execute([$_POST['fio'], $_POST['tel'], $_POST['email'], $_POST['day'] . '.' . $_POST['month'] . '.' . $_POST['year'], $_POST['gender'], $_POST['bio'], true]);
  $id = $db->lastInsertId();

  $stmt = $db->prepare("INSERT INTO person_lang (id_u, id_l) VALUES (:id_u,:id_l)");
  foreach ($_POST['lang'] as $lang) {
    $lang = $db->quote($lang);
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

header('Location: ?save=1');
