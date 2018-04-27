<?php

    // check the TBX-ness of a file
		
    $temp_file_name = $_FILES['upload']['tmp_name'];
    if ($_FILES["upload"]["error"] > 0)
    {
        echo "<p>Error: " . $_FILES["file"]["error"] . "</p>\n";
    }
    
    // Define which version of Perl we will use
    
    $perl = "~/.plenv/versions/5.18.0/bin/perl";
    $lib = "~/perl5/lib/perl5";
    $script = "./main.pl";
        
    $command = "$perl ". "-I $lib " . 
        escapeshellarg($script). ' ' .
        escapeshellarg($temp_file_name);
    
    // Execute the command, store terminal output in the $printed_output variable
    exec($command, $printed_output);

    // If it did work, download a text file using the output stored in the $printed_output variable
    foreach ($printed_output as $line)
    {
        print $line;
    }
