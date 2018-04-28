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
    
    $error = 0;
    $image = "x_red.png";

    //if $ret_val is not 0 or if the log file wasn't created, there was a problem
    if(($ret_val != 0) || (!file_exists($out_file_name)) ){
        #print problems
        header('HTTP/1.1 400 Bad Request');
        if (ob_get_contents()) ob_end_clean();
        flush();
        $error = 1;
    }

    // If it did work, download a text file using the output stored in the $printed_output variable
    else{
        if (preg_match("2018 TBX", $printed_output[0]))
        {
            $image = "check_green.png";
        }
        else if (preg_match("2008 TBX", $printed_output[0]))
        {
            $image = "check_yellow.png";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>TBX Spyglass Results</title>
    
    <link rel="stylesheet" href="./TBXSpyglassWebBridge.css">
</head>

<body>
    <div class="header">
        <span>Go back in browser to return to TBX Spyglass utility.</span>
    </div>
    <h1 class="title">TBX Spyglass Results</h1>
    <div class="container">
        <img class="results_image" src="<? $image ?>"/>
        <p><? 
            if ($error)
            {
                readfile($out_file_name);
            }
            else
            {
                print $printed_output[0];   
            }
            ?></p>
    </div>
</body>
</html>
