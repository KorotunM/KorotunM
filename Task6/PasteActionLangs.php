<?php
    $stmt = $db->prepare("INSERT INTO person_lang (id, id_u, id_l) VALUES (:id, :id_u, :id_l)");
    foreach ($_POST['lang'] as $id_l) {
      $stmt->bindParam(':id', $tmp_id);
      $stmt->bindParam(':id_u', $id_u);
      $stmt->bindParam(':id_l', $id_l);
      $id_u = $id;
      $stmt->execute();
      $tmp_id++;
    }