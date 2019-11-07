<?php
    App::uses( 'CakeEmail', 'Network/Email');

    class ContactsController extends AppController {
        const CONTACT_LIST_LIMIT = 5;

        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('add');
        }

        public function add(){
            if ($this->request->is('post')) {
                if ($this->Contact->save($this->request->data)) {
                    $this->Flash->success(__('Your inquiry has been sent.'));
                    return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
                }
                $this->Flash->error(__('Failed to send inquiry details.'));
            }
        }

        public function index(){
            $this->paginate = array(
                'limit' => self::CONTACT_LIST_LIMIT, // 検索結果を４件ごとに表示する。
            );
            // 一覧表示をpaginate機能で表示させる。
            $this->set('contacts', $this->paginate());
        }

        public function sendContact($contact_id = null){
            self::checkId($contact_id);
            // ajaxに渡すために必要
            $this->set('contact_id', $contact_id);
        }

        public function sendContactAjax(){
            if ($this->request->is(array('ajax'))) {
                $this->autoRender = FALSE; // 設定しないと返却されるデータがhtmlになってしまう。
                $email = new CakeEmail( 'gmail');                        // インスタンス化
                $email->from( array( 'kosukefunteam@gmail.com' => 'Sender'));  // 送信元
                $contact_id = $this->request->data['Contact']['id'];
                $contact = $this->Contact->find('first', array('conditions' => array('id' => $contact_id)));
                $email->to($contact['Contact']['email']);                    // 送信先
                $email->subject( 'お問い合わせの回答');                      // メールタイトル

                $send_msg = $contact['Contact']['name'].
                            "様\n\nお問い合わせの回答として以下の通りとさせていただきます。\n\n".
                            $this->request->data['Contact']['body'];

                $email->send($send_msg);
                $msg = __('Your inquiry has been replied.');
                return json_encode($msg);
            }
        }

        private function checkId($id){
            if (!$id) {
                throw new NotFoundException(__('Invalid contact'));
            }

            // 数値以外なら
            if (!is_numeric($id)) {
                throw new NotFoundException(__('Invalid contact'));
            }

            // idで表現できる最大値を超えていないか
            if (parent::ID_MAX < $id) {
                throw new NotFoundException(__('Invalid contact'));
            }

            $user = $this->User->findById($id);
            if (!$user) {
                throw new NotFoundException(__('Invalid contact'));
            }
        }
    }
?>
