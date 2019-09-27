<?php
  class Image extends AppModel {
    public $belongsTo = 'Post';
    public $actsAs = array(
        'Upload.Upload' => array(
            'image' => array(
                'fields' => array(
                    'dir' => 'image_dir'
                ),
                'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}{field}{DS}',
                'mode' => 0777,
            )
        )
    );
  }
?>
