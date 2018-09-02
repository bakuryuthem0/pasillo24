<?php
return array(
  "driver" => "smtp",
  "host" => "mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "25726b19166714507",
  "password" => "6f1aca10c5c34b",
  "sendmail" => "/usr/sbin/sendmail -bs",
  "pretend" => false
);
/*
return array(

	'driver' => 'smtp',

	'host' => 'smtp.gmail.com',

	'port' => 587,
	
	'driver' => 'sendmail',

	'from' => array('address' => 'prueba@pasillo24.com', 'name' => 'pasillo24.com'),

	'encryption' => 'tls',

	'username' => 'bakuryuthem0@gmail.com',

	'password' => '20990979longth',

	'sendmail' => '/usr/sbin/sendmail -bs',

	'pretend' => false,

);
