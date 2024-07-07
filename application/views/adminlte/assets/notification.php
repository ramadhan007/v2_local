<li class="dropdown messages-menu">
	<?php
    	$rows_notif = get_rows("
			SELECT	a.id, b.id as notif_id, b.title, b.subtitle, b.body, b.notif_date
			FROM 	tb_notif_user AS a
					INNER JOIN tb_notif AS b ON a.`notif_id` = b.`id`
			WHERE	((b.`need_action` AND NOT a.`followed_up`)
					OR (NOT b.`need_action` AND NOT a.`followed_up`))
					AND a.`user_id` = ".$login_data['user']->id."
					AND NOT a.`followed_up`
		");
		$notif_count = count($rows_notif);
		$msg = $notif_count ? "Anda memiliki $notif_count notifikasi" : "Tidak ada notifikasi terbaru";
	?>
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	<i class="fa fa-bell-o"></i>
        <?php if($notif_count): ?>
        <span class="label label-danger"><?=$notif_count;?></span>
        <?php endif; ?>
	</a>
    <ul class="dropdown-menu" style="width:auto">
        <li class="header"><?=$msg;?></li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
            	<?php foreach($rows_notif as $row_notif): ?>
                <li
                	id="notif_item_<?=$row_notif->id;?>"
                	notif_id="notif_item_<?=$row_notif->id;?>"
                	data-title="<?=$row_notif->title;?>"
                    data-body="<?=$row_notif->body;?>"
                    >
                    <a href="#" onclick="javascript:showNotif('<?=$row_notif->id;?>');">
                    <h4 style="margin:0 0 0 0px">
                    	<?=$row_notif->title;?>
                        <small><i class="fa fa-clock-o"></i>
                        <?=timeAgoId($row_notif->notif_date, true);?>
                        </small>
					</h4>
                    <p style="margin:0 0 0 0px">
                    	<?=$row_notif->subtitle;?>
					</p>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="footer">
            <a href="#">Semua Notifikasi</a>
        </li>
    </ul>
</li>

<script type="text/javascript">

function showNotif(notif_user_id){
	$('#notifModalTitle').html($('#notif_item_' + notif_user_id).attr('data-title'));
	$('#notifModalBody').html($('#notif_item_' + notif_user_id).attr('data-body'));
	$('#notifMOdalBtnFollowUp').attr('data-id', notif_user_id);
	$('#notifModal').modal('show');
}

function notifFollowUp(){
	var notif_user_id = $('#notifMOdalBtnFollowUp').attr('data-id');
	$('#notifModal').modal('hide');
	$.post('<?php echo site_url($this->config->item("admin").'/notif/followup'); ?>',
		{
			'notif_user_id' : notif_user_id,
		},
		function(data){
			// status 1 sukses
			// alert(data.status); 
		}, 'json');
}

</script>