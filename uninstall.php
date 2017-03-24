<?php

//if uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();

//add code here to delete options from options tables and drop any custom tables, as appropriate