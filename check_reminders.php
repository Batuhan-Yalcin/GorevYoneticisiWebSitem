<?php
set_time_limit(0); 
ignore_user_abort(true); 

while (true) {

    include 'send_reminders.php';
    
    sleep(300);
}
?> 