<?php

require 'check.php';
check( 'first' );
check( 2, 3 );
for ( $i=0; $i< 10000; $i++ );
check();
sleep( 1 );
check( 'fourth' );


?>
