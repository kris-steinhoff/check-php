<?php

require 'check.php';

check();
for ( $i=0; $i< 2500000; $i++ );
check();
check($i);

check( $GLOBALS );
?>
