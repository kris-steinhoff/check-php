<?php
if ( ! function_exists( 'check_init' ) and ! function_exists( 'check' ) and ! function_exists( 'check_print_html_media' )) {

    $CHECK[ 'id' ] = 0;
    $CHECK[ 'times' ] = array( $CHECK[ 'id' ] => round(microtime( TRUE ) * 1000));
    $CHECK[ 'media_printed' ] = FALSE;
    $CHECK[ 'mode' ] = 'html';
    $CHECK[ 'initialized' ] = FALSE;

    function check_init( $mode = 'html' )
    {
        global $CHECK;

        if ( $CHECK[ 'initialized' ] ) {
            return TRUE;
        }

        $CHECK[ 'mode' ] = $mode;

        switch ( $CHECK[ 'mode' ] ) {
        case 'html':
            check_print_html_media();
            break;
        }

        return $CHECK[ 'initialized' ] = TRUE;
    }

    function check()
    {
        global $CHECK;
        $id = $CHECK[ 'id' ]++;
        $time = $CHECK[ 'times' ][ $id ] = round(microtime( TRUE ) * 1000);
        // die( 'args: '. func_num_args() );
        $timer_mode = func_num_args() < 1;

        check_init();

        $bt = debug_backtrace();
        switch ( $CHECK[ 'mode' ] ) {
        case 'html':

            echo '<div class="check" id="check',$id,'">'."\n";
            echo '<div class="checkinfo">checked at ', basename( $bt[0]['file'] ) ,', line ', $bt[0]['line'] ,'.';
            echo '<span class="checktogglebutton" onclick="checkToggleBacktrace(\'',$id,'\');" id="checktogglebuttonplus',$id,'" style="display: '. ( $timer_mode ? 'none' : 'inline' ) .';">+</span>',"\n";
            echo '<span class="checktogglebutton" onclick="checkToggleBacktrace(\'',$id,'\');" id="checktogglebuttonminus',$id,'" style="display: '. ( $timer_mode ? 'inline' : 'none' ) .';">-</span>',"\n";
            echo '<span class="checktimerbutton" onclick="checkSetStartTime(\'',$id,'\');" id="checktimernutton',$id,'">timer</span>',"\n";
            echo '<span class="checktimercomparebutton" onclick="checkCompareTimes(\'',$id,'\');" id="checktimercomparebutton',$id,'" style="display: none;">compare</span>',"\n";
            echo '</div>',"\n";
            echo '<div class="checkextra" id="checkextra'.$id.'" style="display: '.( $timer_mode ? 'block' : 'none') .';">',"\n";
            echo '<div class="checktimes">',"\n";
            echo '<table>',"\n";
            echo '<tr><th>Page load</th><td>', $time - $CHECK[ 'times' ][0],' ms</td></tr>',"\n";
            if ( count( $CHECK[ 'times' ] ) > 1 ) {
                echo '<tr><th>Last check()</th><td>', $time - $CHECK[ 'times' ][ count( $CHECK[ 'times' ] ) - 2 ],' ms</td></tr>',"\n";
            }
            echo '</table>',"\n";
            echo '</div>',"\n";
            if ( $timer_mode ) {
                echo '</div>',"\n";
            } else {
                echo '<div class="checkbacktrace">';
                $parsed_backtrace ='';
                $show_args = FALSE;
                $offset = 1;
                for ($i=$offset; $i< count( $bt ); $i++) {
                    $parsed_backtrace .= '#'.($i-$offset).' ';
                    if ( isset( $bt[ $i ][ 'file' ] )) {
                        $parsed_backtrace .= $bt[$i]['file'];
                        if ( isset( $bt[ $i ][ 'line' ] )) {
                            $parsed_backtrace .= ', line '.$bt[$i]['line'];
                        }
                    }
                    $parsed_backtrace .= "\n".'function: \'';
                    if (isset($bt[$i]['class'])) {
                        $parsed_backtrace .= $bt[$i]['class'].$bt[$i]['type'];
                    }
                    if ($show_args and is_array($i['args']) and !empty($i['args'])) {
                        foreach ($i['args'] as $pos => $arg) {
                            $parsed_backtrace .= '   arg '.($pos+1).': '.@var_export($arg, TRUE)."\n";
                        }
                    }
                    $parsed_backtrace .= $bt[$i]['function']."()'\n";
                    $parsed_backtrace .= "\n";
                }
                echo $parsed_backtrace;
                echo '</div>',"\n";
                echo '</div>',"\n";
                foreach ( func_get_args() as $var ) {
                    echo '<div class="checkcontents">';
                    if ( $var === NULL ) {
                        echo '<em>NULL</em>'."\n";
                    } else if ( $var === TRUE ) {
                        echo '<em>TRUE</em>'."\n";
                    } else if ( $var === FALSE ) {
                        echo '<em>FALSE</em>'."\n";
                    } else if ( is_string( $var )) {
                        if ( strlen( $var ) == 0 ) {
                            echo '\''.$var.'\' <i>Empty string</i>'."\n";
                        } else {
                            echo '\''.$var."'\n";
                        }
                    } else {
                        print_r( $var );
                    }
                    echo '</div>',"\n";
                }
            }
            echo '<script type="text/javascript">check_times[', $id, '] = ', $time, ';</script>', "\n";
            echo '</div>',"\n";
            echo "\n";
            break;
        case 'text':
            echo 'CHECK ', basename( $bt[0]['file'] ) ,', line ', $bt[0]['line'] ,'.'."\n";
            echo 'Page load: ', $time - $CHECK[ 'times' ][0],' &#181;s',"\n";
            if ( count( $CHECK[ 'times' ] ) > 1 ) {
                echo 'Last check(): ', $time - $CHECK[ 'times' ][ count( $CHECK[ 'times' ] ) - 2 ],' &#181;s',"\n";
            }
            echo '-----'."\n";
            foreach ( func_get_args() as $var ) {
                if ( $var === NULL ) {
                    echo 'NULL'."\n";
                } else if ( $var === TRUE ) {
                    echo 'TRUE (boolean)'."\n";
                } else if ( $var === FALSE ) {
                    echo 'FALSE (boolean)'."\n";
                } else if ( is_string( $var )) {
                    if ( strlen( $var ) == 0 ) {
                        echo '\''.$var.'\' Empty string'."\n";
                    } else {
                        echo '\''.$var."'\n";
                    }
                } else {
                    echo var_export( $var, TRUE )."\n";
                }
                echo '-----'."\n";
            }
            echo "\n\n";
            break;
        }
    }

    function check_print_html_media()
    {
        global $CHECK;

        if ( ! $CHECK[ 'media_printed' ] ) {
            ?>
                <style type="text/css">
                /* style used by check() */
                .check {
border: 1px solid black;
        border-bottom: none;
margin: 5px;
                }

            .checkinfo, .checkcontents, .checktimes, .checkbacktrace {
padding: 3px;
         border-bottom: 1px solid black;
            }

            .checkinfo, .checkextra {
background: #A3BDd2;
            font-size: 13px;
            }

            .checktimes table th, .checktimes table td {
                font-size: 13px;
            }

            .checktimes table th {
                text-align: right;
                font-weight: normal;
            }

            .checktimes table td {
                text-align: right;
                font-family: courier, monospace;
            }

            .checkbacktrace {
background: #A3BDd2;
            white-space: pre-wrap;
            font-family: courier, monospace;
            font-size: 11px;
            }

            .checkcontents {
background: #e3eDf2;
            white-space: pre-wrap;
            font-family: courier, monospace;
            font-size: 12px;
            }

            .checktogglebutton {
float: right;
       font-family: courier, monospace;
       font-size: 16px;
       font-weight: bold;
cursor: pointer;
            }

            .checktimerbutton, .checktimercomparebutton {
float: right;
       margin-right: 5px;
cursor: pointer;
            }
            </style>

                <script type="text/javascript">
                // javascript used by check()
                function checkToggleBacktrace( id )
                {
                    plus = document.getElementById( 'checktogglebuttonplus' + id );
                    minus = document.getElementById( 'checktogglebuttonminus' + id );
                    extra = document.getElementById( 'checkextra' + id );

                    if ( extra.style.display == 'none' ) {
                        plus.style.display = 'none';
                        minus.style.display = 'inline';
                        extra.style.display = 'block';
                    } else {
                        plus.style.display = 'inline';
                        minus.style.display = 'none';
                        extra.style.display = 'none';
                    }
                }

            var compare_times_start;
            var compare_times_end;
            function checkSetStartTime( id )
            {
                compare_times_start = check_times[ id ];
                timers = document.getElementsByClassName( 'checktimerbutton' );
                for ( var i = 0; i < timers.length; i++ ) {
                    timers[ i ].style.display = 'none';
                }
                comps = document.getElementsByClassName( 'checktimercomparebutton' );
                for ( var i = 0; i < comps.length; i++ ) {
                    comps[ i ].style.display = 'inline';
                }
                document.getElementById( 'checktimernutton' + id ).style.display = 'inline';
                document.getElementById( 'checktimercomparebutton' + id ).style.display = 'none';
                document.getElementById( 'checktimernutton' + id ).style.color = 'red';
            }
            function checkCompareTimes( id )
            {
                document.getElementById( 'checktimercomparebutton' + id ).style.color = 'red';
                compare_times_end = check_times[ id ];
                diff = ( compare_times_end - compare_times_start );
                alert( 'Elapsed Time: ' + diff + ' millisecond'+ (( diff != 1 && diff != -1 )  ? 's' : '' ));

                timers = document.getElementsByClassName( 'checktimerbutton' );
                for ( var i = 0; i < timers.length; i++ ) {
                    timers[ i ].style.display = 'inline';
                    timers[ i ].style.color = 'black';
                }
                comps = document.getElementsByClassName( 'checktimercomparebutton' );
                for ( var i = 0; i < comps.length; i++ ) {
                    comps[ i ].style.display = 'none';
                    comps[ i ].style.color = 'black';
                }

            }

            var check_times = new Array();
            <? /* check_times[0] = <?=@$CHECK[ 'times' ][0]?> */ ?>;
            </script>
                <?
                $CHECK[ 'media_printed' ] = TRUE;
        }
    }
}

if ( ! function_exists( 'timer' )) {
    function timer( $note = NULL )
    {
        static $last;
        $current = microtime( TRUE );

        echo 'current: '. $current ."\n";
        if ( isset( $last )) {
            echo 'elapsed: '. (( $current - $last ) * 1000 ) ."\n";
        } else {
            echo 'elapsed: N/A'."\n";
        }
        if ( $note !== NULL ) {
            echo $note ."\n";
        }
        echo "\n";
        $last = $current;
    }
}
?>
