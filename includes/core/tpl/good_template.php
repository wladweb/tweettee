
<h4>Good Template</h4>
<pre>
<?php
    //print_r($data);
    foreach($this->result->statuses as $twit){
        print $twit->text . "<hr>\r\n\r\n";
    }
?>