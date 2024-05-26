<?php
    $sth = $db->prepare("SELECT * FROM person_lang");
    $sth->execute();
    $users_langs = $sth->fetchAll();