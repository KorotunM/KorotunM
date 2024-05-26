<?php
include('../password.php');
$sth = $db->prepare("SELECT * FROM admin_login");
$sth->execute();
$admin_lp = $sth->fetchAll();
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $admin_lp[0]['login_a'] ||
    md5($_SERVER['PHP_AUTH_PW']) != $admin_lp[0]['pass_a']) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные.');
if (!empty($_COOKIE['save'])) {
  print('<br>');
  print('Операция выполнена успешно.');
  setcookie('save', '', 100000);
  setcookie('PHPSESSID', '', 100000, '/');
  setcookie('fio_value', '', 100000);
  setcookie('tel_value', '', 100000);
  setcookie('email_value', '', 100000);
  setcookie('year_value', '', 100000);
  setcookie('month_value', '', 100000);
  setcookie('day_value', '', 100000);
  setcookie('gender_value', '', 100000);
  setcookie('lang_value', '', 100000);
  setcookie('bio_value', '', 100000);
  setcookie('check_value', '', 100000);
}
setcookie('admin', $admin_lp[0]['pass_a'], time() + 24 * 60 * 60);
include('SelectDataUsers.php');
?>

<h2>Таблица пользователей</h2>
<table class="users">
  <tr>
    <th>ID</th>
    <th>ФИО</th>
    <th>Телефон</th>
    <th>Email</th>
    <th>Дата рождения</th>
    <th>Пол</th>
    <th>Биография</th>
    <th class="nullCell"></th>
    <th class="nullCell"></th>
  </tr>
  <?php
    foreach($users as $user) {
      printf('<tr>
      <td>%d</td>
      <td>%s</td>
      <td>%s</td>
      <td>%s</td>
      <td>%s</td>
      <td>%s</td>
      <td>%s</td>
      <td class="nullCell">
        <form action="delete_change_event.php" method="POST">
          <input type="hidden" name="action" value="change">
          <input type="hidden" name="id" value="%d">
          <input type="submit" class="tableButtonCh" value="изменить"/>
        </form>
      </td>
      <td class="nullCell">
        <form action="delete_change_event.php" method="POST">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="%d">
          <input type="submit" class="tableButtonDel" value="удалить"/>
        </form>
      </td>
      </tr>',
      $user['id'], $user['fio'], $user['tel'], $user['email'],
      $user['bornday'], $user['gender'], $user['bio'],
      $user['id'], $user['id']);
    }
  ?>
</table>

<?php
$sth = $db->prepare("SELECT pl.id_u, l.language 
FROM person_lang pl  join Lang l on pl.id_l = l.id");
$sth->execute();
$users_lang = $sth->fetchAll();
?>

<h2>Языки программирования пользователей</h2>
<table class="languages">
  <tr>
    <th>ID пользователя</th>
    <th>Язык программирования</th>
  </tr>
  <?php
    foreach($users_lang as $user_lang) {
      printf('<tr>
      <td>%s</td>
      <td>%s</td>
      </tr>',
      $user_lang['id_u'], $user_lang['language']);
    }
  ?>
</table>

<h2>Общая статистика популярности языков програмирования</h2>
<table class="user_count">
  <tr>
    <th>Язык программирования</th>
    <th>Количество выбравших людей</th>
  </tr>
  <?php
    $sth = $db->prepare("SELECT l.language AS language_name, COUNT(pl.id_u) AS user_count FROM Lang l LEFT JOIN person_lang pl ON l.id = pl.id_l GROUP BY l.language");
    $sth->execute();
    $user_count = $sth->fetchAll();
    foreach($user_count as $u_c) {
      printf('<tr>
      <td>%s</td>
      <td>%s</td>
      </tr>',
      $u_c['language_name'], $u_c['user_count']);
    }
  ?>
</table>

<form action="index.php" method="GET">
  <input type="submit" class="finalBut Button" name="exit_admin" value="Выход">
</form>

