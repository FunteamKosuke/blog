<?php
    if ($retransmission_flg) {
        $retransmission_address = '/users/retransmission/' . $user_id_hash;
        echo $this->Html->link( __('Resend this registration email'), $retransmission_address);
    } else {
        echo $this->Html->link( __('To login screen'), '/users/login');
    }
?>
