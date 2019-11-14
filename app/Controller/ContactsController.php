<?php
    App::uses( 'CakeEmail', 'Network/Email');

    class ContactsController extends AppController {
        const CONTACT_LIST_LIMIT = 5;

        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow('add', 'complete');
        }

        public function add(){
            if ($this->request->is('post')) {
                if ($this->request->data['mode'] === 'confirm') {
                    // 確認画面へ行く前にバリデーションチェックをする。
                    $this->Contact->set($this->request->data);
                    if (!$this->Contact->validates()) {
                        $this->Session->error('入力内容に不備があります。');
                        return;
                    }
                    $this->set('data', $this->request->data['Contact']);
                    $this->render('confirm');
                } else {
                    if ($this->Contact->save($this->request->data)) {
                        $this->Flash->success(__('Your inquiry has been sent.'));
                        return $this->redirect(array('action' => 'thanks'));
                    }
                    $this->Flash->error(__('Failed to send inquiry details.'));
                }
            }
        }

        public function index(){
            $this->paginate = array(
                'limit' => self::CONTACT_LIST_LIMIT,
            );
            // 一覧表示をpaginate機能で表示させる。
            $this->set('contacts', $this->paginate());
        }

        public function thanks(){

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

        public function delete($contact_id = null){
            if ($this->request->is('get')) {
                throw new MethodNotAllowedException();
            }

            self::checkId($contac_id);

            if ($this->Contact->delete($contact_id)) {
                $this->Flash->success(
                    __('The Contact with id: %s has been deleted.', h($contact_id))
                );
            } else {
                $this->Flash->error(
                    __('The Contact with id: %s could not be deleted.', h($contact_id))
                );
            }

            return $this->redirect(array('action' => 'index'));
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

            $user = $this->Contact->findById($id);
            if (!$user) {
                throw new NotFoundException(__('Invalid contact'));
            }
        }
    }
?>
