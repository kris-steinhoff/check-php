<?php
$CHECK[ 'id' ] = 0;
$CHECK[ 'times' ] = array( $CHECK[ 'id' ] => round(microtime( TRUE ) * 1000));
$CHECK[ 'media_printed' ] = FALSE;

function check()
{
    global $CHECK;
    $id = $CHECK[ 'id' ]++;
    $time = $CHECK[ 'times' ][ $id ] = round(microtime( TRUE ) * 1000);
    // die( 'args: '. func_num_args() );
    $timer_mode = func_num_args() < 1;

    check_print_media();

    $bt = debug_backtrace();

    echo '<div class="check" id="check',$id,'">'."\n";
    echo '<div class="checkinfo">checked at ', basename( $bt[0]['file'] ) ,', line ', $bt[0]['line'] ,'.';
    echo '<span class="checktogglebutton" onclick="checkToggleBacktrace(\'',$id,'\');" id="checktogglebuttonplus',$id,'">+</span>',"\n";
    echo '<span class="checktogglebutton" onclick="checkToggleBacktrace(\'',$id,'\');" id="checktogglebuttonminus',$id,'" style="display: none;">-</span>',"\n";
    echo '<span class="checktimerbutton" onclick="checkSetStartTime(\'',$id,'\');" id="checktimernutton',$id,'">timer</span>',"\n";
    echo '<span class="checktimercomparebutton" onclick="checkCompareTimes(\'',$id,'\');" id="checktimercomparebutton',$id,'" style="display: none;">compare</span>',"\n";
    echo '</div>',"\n";
    echo '<div class="checkextra" id="checkextra'.$id.'" style="display: '.( $timer_mode ? 'block' : 'none') .';">',"\n";
    echo '<div class="checktimes">',"\n";
    echo '<table>',"\n";
    echo '<tr><th>Page load</th><td>', $time - $CHECK[ 'times' ][0],' ms</td></tr>',"\n";
    echo '<tr><th>Last check()</th><td>', $time - $CHECK[ 'times' ][ count( $CHECK[ 'times' ] ) - 2 ],' ms</td></tr>',"\n";
    echo '</table>',"\n";
    echo '</div>',"\n";
    if ( $timer_mode ) {
        echo '</div>',"\n";
    } else {
        echo '<div class="checkbacktrace">';
        debug_print_backtrace();
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
                if ( empty( $var )) {
                    echo '\''.$var.'\' <em>Empty string</em>'."\n";
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
}

function check_print_media()
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
            alert( 'Elapsed Time: ' + diff + ' ms'  );

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
        check_times[0] = <?=$CHECK[ 'times' ][0]?>;
        </script>
            <?
            $CHECK[ 'media_printed' ] = TRUE;
    }
}
?>
