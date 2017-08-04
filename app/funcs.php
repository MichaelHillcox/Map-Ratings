<?php
    function showError( $message ) {
        $render = "<div class='error'>
            <p>
                {$message}
            </p>
        </div>";

        die( $render );
    }

    function formatName($name) {
    	$name = str_replace(["mp_dr_", "mp_deathrun_", "mp_"], "", $name);
    	$name = str_replace("_", " ", $name);
    	return ucwords($name);
	}