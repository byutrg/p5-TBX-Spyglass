<?php

    // check the TBX-ness of a file

    $temp_file_name = $_FILES['upload']['tmp_name'];
    if ($_FILES["upload"]["error"] > 0)
    {
        echo "<p>Error: " . $_FILES["file"]["error"] . "</p>\n";
    }
    $out_file_name = $temp_file_name . '.log';

    // Define which version of Perl we will use

    $perl = "~/.plenv/versions/5.18.0/bin/perl";
    $lib = "~/perl5/lib/perl5";
    $script = "./main.pl";

    $reroute_stderr = "2>$out_file_name";

    $command = "$perl ". "-I $lib " .
        escapeshellarg($script). ' ' .
        escapeshellarg($temp_file_name) . ' -s ' .
            $reroute_stderr;

    $ret_val = 0;

    // Execute the command, store terminal output in the $printed_output variable
    exec($command, $printed_output, $ret_val);

    //if $ret_val is not 0 or if the log file wasn't created, there was a problem
    if(($ret_val != 0) || (!file_exists($out_file_name)) ){
        #print problems
        header('HTTP/1.1 400 Bad Request');
        if (ob_get_contents()) ob_end_clean();
        flush();
        readfile($out_file_name);
    }

    // If it did work, download a text file using the output stored in the $printed_output variable
    else{
        foreach ($printed_output as $line)
        {
            print "<p>$line</p>";
        }    
    }
    print "<br/>";
    print "<p>Go back in browser to return to TBX Spyglass utility.</p>";
