<?php
$i = 0;
set_time_limit(120);

while ($i<100)
{
sleep (5);
echo "at loop $i<br>\n";
flush();
$i++;
}
