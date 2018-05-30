<?php
/*
    Copyright 2011 Emerginov Team <admin@emerginov.org>
    
    This file is part of Emerginov Scripts.

    This script is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This script is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this script.  If not, see <http://www.gnu.org/licenses/>.

*/

if (isset($_REQUEST['file']) && file_exists($_REQUEST['file'])){
    $file = $_REQUEST['file'];
    
    // Load english as default language
    include('english.php');
    $langDefault = $langArray;
    
    // Load the file as compare language
    include($file);
    
    // Now check for missing values in $file array
    $diffArray =  array_diff_key($langDefault, $langArray);
    
    // Prepare the report and the string to be added
    $toAddInFile = "";
    foreach ($diffArray as $key => $value){
        echo "Missing $key on $file array<br/>";
        $toAddInFile .= "\"$key\" => \"$value\",\n";
    }
    
    if ($toAddInFile != ""){
        echo "Please add the following keys to the \$langArray array in $file. Then, do not forget to translate words!<br/>";
        echo "<br/><br/><pre>$toAddInFile</pre><br/><br/>";
    }
    else{
        echo "File is up to date!";
    }
    
    /*
    echo "<pre>";
    print_r($langDefault);
    print_r($langArray);
    echo "</pre>";
    */
}
?>
<br/>
<form method='get' action='checkdiff.php' >
    <input type='text' value='<?php echo (isset($_REQUEST['file']) && file_exists($_REQUEST['file'])) ? $_REQUEST['file'] : "french.php";  ?>' name='file' />
    <input type='submit' value='Go!' />
</form>
