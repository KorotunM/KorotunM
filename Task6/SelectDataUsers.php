<?php
    $sth = $db->prepare("SELECT * FROM Person");
    $sth->execute();
    $users = $sth->fetchAll();