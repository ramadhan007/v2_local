<?php

$i = 0 + $offset;
$j = 0;
$numrows = count($rows_error);
foreach($rows_error as $row):
?>
<tr>
    <td class="name"><?=$row->message;?></td>
    <td class="value"><?=$row->total;?></td>
</tr>
<?php endforeach; ?>