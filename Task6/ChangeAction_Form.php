<?php
include('../password.php');
$uid = $_SESSION['uid'];
$sth = $db->prepare("SELECT * FROM Person where id = $uid");
$sth->execute();
$user = $sth->fetchAll();
$values['fio'] = strip_tags($user[0]['fio']);
$values['tel'] = strip_tags($user[0]['tel']);
$values['email'] = strip_tags($user[0]['email']);
$pos1 = strpos(strip_tags($user[0]['bornday']),'.');
$values['day']=strip_tags(intval(substr($user[0]['bornday'], 0, $pos1)));
$pos2 = strrpos(strip_tags($user[0]['bornday']),'.');
$values['month']=strip_tags(intval(substr($user[0]['bornday'], $pos1 + 1, $pos2 - $pos1 - 1)));
$values['year']=strip_tags(intval(substr($user[0]['bornday'], $pos2 + 1, 4)));
$values['gender'] = strip_tags($user[0]['gender']);

$sth = $db->prepare("SELECT id_l FROM person_lang where id_u = $uid");
$sth->execute();
$languages = $sth->fetchAll();
$values['lang'] = array();
foreach($languages as $l) {
  array_push($values['lang'], $l['id_l']);
}
$values['bio'] = strip_tags($user[0]['bio']);
$values['check'] = strip_tags($user[0]['checked']);
