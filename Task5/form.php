<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Drupal Coder</title>
  <link rel="stylesheet" href="style.css">
  <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
  </style>
</head>

<body>

  <form action="index.php" method="POST">
    <h2 id="section3">Красивая форма</h2>

    <label>
      Введите инициалы:<br />
      <input name="fio" type = "text" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" placeholder="ФИО" />
    </label><br />
    <?php if ($errors['fio']) {print($messages['fio']); print('<br>');}?>

    <label>
      Введите телефон:<br />
      <input name="tel" type="tel" <?php if ($errors['tel']) {print 'class="error"';} ?> value="<?php print $values['tel']; ?>" placeholder="номер" />
    </label><br />
    <?php if ($errors['tel']) {print($messages['tel']); print('<br>');}?>

    <label>
      Введите email:<br />
      <input name="email" type="email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" placeholder="почта" />
    </label><br />
    <?php if ($errors['email']) {print($messages['email']); print('<br>');}?>

    <label>
      Укажите год рождения:<br />
      <select name="year" <?php if ($errors['year']) {print 'class="error"';} ?>>
        <?php
        for ($i = 1922; $i <= 2022; $i++) {
          printf('<option %s value="%d">%d год</option>',$values['year'] == $i ? 'selected' : '', $i, $i);
        }
        ?>
      </select><br />
      <?php if ($errors['year']) {print($messages['year']); print('<br>');}?>

      Укажите месяц рождения:<br />
      <select name="month"  <?php if ($errors['month']) {print 'class="error"';} ?> >
        <?php
        for ($i = 1; $i <= 12; $i++) {
          printf('<option %s value="%d">%d месяц</option>',$values['month'] == $i ? 'selected' : '', $i, $i);
        }
        ?>
      </select><br />
      <?php if ($errors['month']) {print($messages['month']); print('<br>');}?>

      Укажите день рождения:<br />
      <select name="day"  <?php if ($errors['day']) {print 'class="error"';} ?> >
        <?php
        for ($i = 1; $i <= 31; $i++) {
          printf('<option %s value="%d">%d день</option>',$values['day'] == $i ? 'selected' : '', $i, $i);
        }
        ?>
      </select>
      <?php if ($errors['day']) {print($messages['day']); print('<br>');}?>

    </label><br />

    Укажите пол:<br />
    <label class="gd_rad <?php if ($errors['gender']) {print " error";} ?>">
      <input type="radio" name="gender" value="man" <?php if ($values['gender'] == 'man') print('checked'); ?> /> мужской
      <input type="radio" name="gender" value="woman" <?php if ($values['gender'] == 'woman') print('checked'); ?> />женский
    </label><br />
    <?php if ($errors['gender']) {print($messages['gender']); print('<br>');}?>

    <label>
      Выберите любимый язык:
      <br />
      <select name="lang[]" multiple="multiple" <?php if ($errors['lang']) {print 'class="error"';} ?>>
        <option <?php check_lang($values, "1")?> value="1">С</option>
        <option <?php check_lang($values, "2")?> value="2">Pascal</option>
        <option <?php check_lang($values, "3")?> value="3">Scala</option>
        <option <?php check_lang($values, "4")?> value="4">C++</option>
        <option <?php check_lang($values, "5")?> value="5">Java</option>
        <option <?php check_lang($values, "6")?> value="6">Python</option>
        <option <?php check_lang($values, "7")?> value="7">JavaScript</option>
        <option <?php check_lang($values, "8")?> value="8">PHP</option>
        <option <?php check_lang($values, "9")?> value="9">Hascel</option>
        <option <?php check_lang($values, "10")?> value="10">Clojure</option>
        <option <?php check_lang($values, "11")?> value="11">Prolog</option>
      </select>
    </label><br />
    <?php if ($errors['lang']) {print($messages['lang']); print('<br>');}?>
    
    <label>
      Краткая биография:<br />
      <textarea name="bio" <?php if ($errors['bio']) {print 'class="error"';} ?> > <?php print $values['bio']; ?> </textarea>
    </label><br />
    <?php if ($errors['bio']) {print($messages['bio']); print('<br>');}?>

    <label class="checking <?php if ($errors['check']) {print " error";} ?>">
    С передачей данных:<br />
    <input type="checkbox" name="check" <?php if (!empty($values['check'])) {print('checked');} ?>/> Согласен-а
    </label><br />
    <?php if ($errors['check']) {print($messages['check']); print('<br>');}?>
    <input type="submit" value="Сохранить" />
  </form>
</body>

</html>