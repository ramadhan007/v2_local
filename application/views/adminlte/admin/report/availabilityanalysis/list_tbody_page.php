<?php

$i = 0 + $offset;
$j = 0;
$numrows = count($rows_page);
foreach($rows_page as $row):
?>
<tr>
    <td class="name"><?=$row->journey_detail_name;?></td>
    <td class="value"><?=$row->total;?></td>
</tr>
<?php endforeach; ?>