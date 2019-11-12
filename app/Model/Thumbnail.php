<?php
  class Thumbnail extends AppModel {
    public $hasOne = 'Post';
    public $actsAs = array(
        'Upload.Upload' => array(
            'thumbnail' => array(
                'fields' => array(
                    'dir' => 'thumbnail_dir'
                ),
                // 'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}{field}{DS}',
                // 'mode' => 0777,
            )
        )
    );
    public $validate = array(
        'thumbnail' => array(

            // ルール：uploadError => errorを検証 (2.2 以降)
            'upload-file' => array(
                'rule' => array( 'uploadError'),
                'message' => array( 'Error uploading file')
            ),

            // ルール：extension => pathinfoを使用して拡張子を検証
            'extension' => array(
                'rule' => array( 'extension', array(
                    'jpeg', 'jpg')  // 拡張子を配列で定義
                ),
                'message' => array( 'file extension error')
            ),

            // ルール：mimeType =>
            // finfo_file(もしくは、mime_content_type)でファイルのmimeを検証 (2.2 以降)
            // 2.5 以降 - MIMEタイプを正規表現(文字列)で設定可能に
            'mimetype' => array(
                'rule' => array( 'mimeType', array(
                    'image/jpeg')  // MIMEタイプを配列で定義
                ),
                'message' => array( 'MIME type error')
            ),

            // ルール：fileSize => filesizeでファイルサイズを検証(2GBまで設定可能)  (2.3 以降)
            'size' => array(
                'maxFileSize' => array(
                    'rule' => array( 'fileSize', '<=', '512MB'),  // 10M以下
                    'message' => array( 'file size error')
                ),
                'minFileSize' => array(
                    'rule' => array( 'fileSize', '>',  0),    // 0バイトより大
                    'message' => array( 'file size error')
                ),
            ),
        ),
    );
  }
?>
