<?php
global $dump_id, $dump_times, $dump_media_printed;
$dump_id = 0;
$dump_times = array( $dump_id => round(microtime( TRUE ) * 1000));
$dump_media_printed = FALSE;

function dump( $var = NULL )
{
    global $dump_id, $dump_times;
    $dump_id++;
    $dump_times[$dump_id] = round(microtime( TRUE ) * 1000);
    dump_print_media();

    $bt = debug_backtrace();

    echo '<div class="dump" id="dump',$dump_id,'">'."\n";
    echo '<div class="dumpinfo">Dumped at ', basename( $bt[0]['file'] ) ,', line ', $bt[0]['line'] ,'.';
    echo '<span class="dumptogglebutton" onclick="dumpToggleBacktrace(\'',$dump_id,'\');" id="dumptogglebuttonplus',$dump_id,'">+</span>',"\n";
    echo '<span class="dumptogglebutton" onclick="dumpToggleBacktrace(\'',$dump_id,'\');" id="dumptogglebuttonminus',$dump_id,'" style="display: none;">-</span>',"\n";
    echo '<span class="dumptimerbutton" onclick="dumpSetStartTime(\'',$dump_id,'\');" id="dumptimernutton',$dump_id,'">timer</span>',"\n";
    echo '<span class="dumptimercomparebutton" onclick="dumpCompareTimes(\'',$dump_id,'\');" id="dumptimercomparebutton',$dump_id,'" style="display: none;">compare</span>',"\n";
    echo '</div>',"\n";
    echo '<div class="dumpextra" id="dumpextra',$dump_id,'" style="display: none;">',"\n";
    echo '<div class="dumptimes">',"\n";
    echo '<table>',"\n";
    echo '<tr><th>Page load</th><td>', $dump_times[$dump_id] - $dump_times[0],' ms</td></tr>',"\n";
    echo '<tr><th>Last dump()</th><td>', $dump_times[$dump_id] - $dump_times[ count( $dump_times ) - 2 ],' ms</td></tr>',"\n";
    echo '</table>',"\n";
    echo '</div>',"\n";
    echo '<div class="dumpbacktrace">';
    debug_print_backtrace();
    echo '</div>',"\n";
    echo '</div>',"\n";
    foreach ( func_get_args() as $var ) {
        echo '<div class="dumpcontents">';
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
    echo '<script type="text/javascript">dump_times[', $dump_id, '] = ', $dump_times[$dump_id], ';</script>', "\n";
    echo '</div>',"\n";
    echo "\n";
}

function dump_timer()
{
    global $dump_id, $dump_times;
    $dump_id++;
    $dump_times[$dump_id] = round(microtime( TRUE ) * 1000);
    dump_print_media();

    $bt = debug_backtrace();
    echo '<div class="dump" id="dump',$dump_id,'">'."\n";
    echo '<div class="dumpinfo">Dumped at ', basename( $bt[0]['file'] ) ,', line ', $bt[0]['line'] ,'.';
    echo '<span class="dumptimerbutton" onclick="dumpSetStartTime(\'',$dump_id,'\');" id="dumptimernutton',$dump_id,'">timer</span>',"\n";
    echo '<span class="dumptimercomparebutton" onclick="dumpCompareTimes(\'',$dump_id,'\');" id="dumptimercomparebutton',$dump_id,'" style="display: none;">compare</span>',"\n";
    echo '</div>',"\n";
    echo '<div class="dumpextra" id="dumpextra',$dump_id,'" style="display: block;">',"\n";
    echo '<div class="dumptimes">',"\n";
    echo '<table>',"\n";
    echo '<tr><th>Page load</th><td>', $dump_times[$dump_id] - $dump_times[0],' ms</td></tr>',"\n";
    echo '<tr><th>Last dump()</th><td>', $dump_times[$dump_id] - $dump_times[ count( $dump_times ) - 2 ],' ms</td></tr>',"\n";
    echo '</table>',"\n";
    echo '</div>',"\n";
    echo '</div>',"\n";
    echo '<script type="text/javascript">dump_times[', $dump_id, '] = ', $dump_times[$dump_id], ';</script>', "\n";
    echo '</div>',"\n";
    echo "\n";
}

function dump_print_media()
{
    global $dump_media_printed;

    if ( ! $dump_media_printed ) {
        ?>
            <style type="text/css">
            /* style used by dump() */
            .dump {
border: 1px solid black;
        border-bottom: none;
margin: 5px;
            }

        .dumpinfo, .dumpcontents, .dumptimes, .dumpbacktrace {
padding: 3px;
         border-bottom: 1px solid black;
        }

        .dumpinfo, .dumpextra {
background: #A3BDd2;
            font-size: 13px;
        }

        .dumptimes table th, .dumptimes table td {
            font-size: 13px;
        }

        .dumptimes table th {
            text-align: right;
            font-weight: normal;
        }

        .dumptimes table td {
            text-align: right;
            font-family: courier, monospace;
        }

        .dumpbacktrace {
background: #A3BDd2;
            white-space: pre-wrap;
            font-family: courier, monospace;
            font-size: 11px;
        }

        .dumpcontents {
background: #e3eDf2;
            white-space: pre-wrap;
            font-family: courier, monospace;
            font-size: 12px;
        }

        .dumptogglebutton {
float: right;
       font-family: courier, monospace;
       font-size: 16px;
       font-weight: bold;
cursor: pointer;
        }

        .dumptimerbutton, .dumptimercomparebutton {
float: right;
       margin-right: 5px;
cursor: pointer;
        }
        </style>

            <script type="text/javascript">
            // javascript used by dump()
            function dumpToggleBacktrace( id )
            {
                plus = document.getElementById( 'dumptogglebuttonplus' + id );
                minus = document.getElementById( 'dumptogglebuttonminus' + id );
                extra = document.getElementById( 'dumpextra' + id );

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
        function dumpSetStartTime( id )
        {
            compare_times_start = dump_times[ id ];
            timers = document.getElementsByClassName( 'dumptimerbutton' );
            for ( var i = 0; i < timers.length; i++ ) {
                timers[ i ].style.display = 'none';
            }
            comps = document.getElementsByClassName( 'dumptimercomparebutton' );
            for ( var i = 0; i < comps.length; i++ ) {
                comps[ i ].style.display = 'inline';
            }
            document.getElementById( 'dumptimernutton' + id ).style.display = 'inline';
            document.getElementById( 'dumptimercomparebutton' + id ).style.display = 'none';
            document.getElementById( 'dumptimernutton' + id ).style.color = 'red';
        }
        function dumpCompareTimes( id )
        {
            document.getElementById( 'dumptimercomparebutton' + id ).style.color = 'red';
            compare_times_end = dump_times[ id ];
            diff = ( compare_times_end - compare_times_start );
            alert( 'Elapsed Time: ' + diff + ' ms'  );

            timers = document.getElementsByClassName( 'dumptimerbutton' );
            for ( var i = 0; i < timers.length; i++ ) {
                timers[ i ].style.display = 'inline';
                timers[ i ].style.color = 'black';
            }
            comps = document.getElementsByClassName( 'dumptimercomparebutton' );
            for ( var i = 0; i < comps.length; i++ ) {
                comps[ i ].style.display = 'none';
                comps[ i ].style.color = 'black';
            }

        }

        var dump_times = new Array();
        dump_times[0] = <?=$dump_times[0]?>;
        </script>
            <?
            $dump_media_printed = TRUE;
    }
}
?>
