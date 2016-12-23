<?php

/*Database settings*/
#database host
define('DBHOST', 'localhost');
#database name
define('DBNAME', 'spl');
#database user
define('DBUSER', 'spl');
#database password
define('DBPASS', 'spl');

function earth_radius($msr = 0)
{   
    $msr = (int)$msr;
    if ($msr === 0)
    {
    	// miles
        return 3959;
    }
    elseif ($msr === 1) 
    {
    	//kilometers
        return 6371;
    }
}
