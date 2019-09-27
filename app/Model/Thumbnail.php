<?php
  class Thumbnail extends AppModel {
    public $hasOne = 'Post';
    public $actsAs = array(
        'Upload.Upload' => array(
            'thumbnail' => array(
                'fields' => array(
                    'dir' => 'thumbnail_dir'
                ),
                'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}{field}{DS}',
                'mode' => 0777,
            )
        )
    );
  }
?>
