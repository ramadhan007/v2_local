<?php
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
                $cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
                $link = anchor($this->controller.'/edit/'.$row->id,$row->tag,array('class'=>'link'));
            ?>
            <tr>
                <td><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><?php echo ucwords($row->category); ?></td>
                <td><?php echo $link; ?></td>
                <td><?php echo $row->subject; ?></td>
            </tr>
            <?php endforeach; ?>