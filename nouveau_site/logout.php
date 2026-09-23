<?php

require __DIR__ . '/app/bootstrap.php';

$auth->logout();
redirect('login.php');

