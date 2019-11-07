<?php
    class Contact extends AppModel {
        public $validate = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notBlank',
                    'message' => 'This is a required input item.'
                )
            ),
            'email' => array(
                'rule1' => array(
                    'rule' => 'notBlank',
                    'message' => 'This is a required input item.'
                ),
                // メールアドレスであること。
                'validEmail' => array( 'rule' => array( 'email', true),
                                        'message' => 'Please enter your e-mail address')
            ),
            'body' => array(
                'rule1' => array(
                    'rule' => 'notBlank',
                    'message' => 'This is a required input item.'
                )
            ),
        );
    }
?>
