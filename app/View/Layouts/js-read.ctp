<?php
    // jQuery
    echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
    echo $this->Html->script('http://code.jquery.com/ui/1.12.1/jquery-ui.min.js');

    // bootstrap
    echo $this->Html->script('bootstrap/bootstrap.min.js');

    // モーダルダイアログのjsの２重読み込み防止
    if ($this->action == 'sendMsg' || $this->action == 'sendContact'){
        echo $this->Html->script('modal.js');
    } else {
        echo $this->Html->script('common.js');
    }
    // echo $this->Html->script('common.js');

?>
