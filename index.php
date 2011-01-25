<?php

require 'check.php';
check_init();

check();
for ( $i=0; $i< 2500000; $i++ );
check();
check($i);

check( $GLOBALS );
?>
