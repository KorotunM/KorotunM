<?php
    $stmt = $db->prepare("DELETE FROM person_lang where id_u = ?");
    $stmt->execute([$id]);
    include('SelectLangUser.php');
    $countId = count($users_langs);
    $index = 0;
    for ($i = 1; $i <= $countId; $i++) {
        $tempUL = intval($users_langs[$index]['id']);
        $stmt = $db->prepare("UPDATE person_lang SET id = ? where id = $tempUL");
        $stmt->execute([$i]);
        $index++;
    }
