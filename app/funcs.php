<?php
    function showError( $message ) {
        $render = "<div class='error'>
            <p>
                {$message}
            </p>
        </div>";

        die( $render );
    }
