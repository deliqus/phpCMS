<?php

function log_as_json($vars)
{
    if(is_resource($vars)) {
        return;
    }
    else {
        if($vars)
        {
            $json =  @json_encode($vars);
            print "<script>console.log($json);</script>";
        }

    }
}

?>