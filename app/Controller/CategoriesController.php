<?php
  class CategoriesController extends AppController {
    public $uses = array('Post', 'Category');
    public function add() {
        if ($this->request->is('post')) {
            $this->Category->create();
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('Your Category has been saved.'));
                return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your Category.'));
        }
    }

    public function related_post_index($id = null){
        self::checkId($id);

        $this->paginate = array( 'Post' => array(
            'conditions' => array('category_id' => $id,
                                    'publish_flg' => parent::PUBLISH), // 検索する条件を設定する。
            'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
        ));
        // 一覧表示をpaginate機能で表示させる。
        $this->set('posts', $this->paginate());
    }
    private function checkId($id){
        if (!$id) {
            throw new NotFoundException(__('Invalid tag'));
        }

        // 数値以外なら
        if (!is_numeric($id)) {
            throw new NotFoundException(__('Invalid tag'));
        }

        // idで表現できる最大値を超えていないか
        if (parent::ID_MAX < $id) {
            throw new NotFoundException(__('Invalid tag'));
        }

        $category = $this->Category->findById($id);
        if (!$category) {
            throw new NotFoundException(__('Invalid tag'));
        }
    }
  }
?>
