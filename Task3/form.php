<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Drupal Coder</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <form action="index.php" method="POST">
    <h2 id="section3">Красивая форма</h2>
    <label>
      Введите инициалы:<br />
      <input name="fio" value="ФИО" />
    </label><br />
    <label>
      Введите телефон:<br />
      <input name="tel" type="tel" placeholder="номер" />
    </label><br />
    <label>
      Введите email:<br />
      <input name="email" type="email" placeholder="почта" />
    </label><br />
    <label>
      Укажите год рождения:<br />
      <select name="year">
        <?php
        for ($i = 1922; $i <= 2022; $i++) {
          printf('<option value="%d">%d год</option>', $i, $i);
        }
        ?>
      </select>
      Укажите месяц рождения:<br />
      <select name="month">
        <?php
        for ($i = 1; $i <= 12; $i++) {
          printf('<option value="%d">%d месяц</option>', $i, $i);
        }
        ?>
      </select>
      Укажите день рождения:<br />
      <select name="day">
        <?php
        for ($i = 1; $i <= 31; $i++) {
          printf('<option value="%d">%d день</option>', $i, $i);
        }
        ?>
      </select>
    </label>
    <br />
    Укажите пол:<br />
    <label><input type="radio" checked="checked" name="radio-group-1" value="мужской" />
      мужской</label>
    <label><input type="radio" name="radio-group-1" value="женский" />
      женский</label><br />
    <label>
      Выберите любимый язык:
      <br />
      <select name="name-4[]" multiple="multiple">
        <option value="1">С</option>
        <option value="2" selected="selected">Pascal</option>
        <option value="3" selected="selected">Scala</option>
        <option value="4" selected="selected">C++</option>
        <option value="5" selected="selected">Java</option>
        <option value="6" selected="selected">Python</option>
        <option value="7" selected="selected">JavaScript</option>
        <option value="8" selected="selected">PHP</option>
        <option value="9" selected="selected">Hascel</option>
        <option value="10" selected="selected">Clojure</option>
        <option value="11" selected="selected">Prolog</option>
      </select>
    </label><br />

    <label>
      Краткая биография:<br />
      <textarea name="name-2">Расскажите о себе</textarea>
    </label><br />
    С передачей данных:<br />
    <label><input type="checkbox" checked="checked" name="check-1" />
      Согласен-а</label><br />
    <input type="submit" value="Сохранить" />
  </form>
</body>

</html>