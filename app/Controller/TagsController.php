<?php
    class TagsController extends AppController {
      public $uses = array('Post', 'Tag','PostsTag');
        public function add() {
            if ($this->request->is('post')) {
                if ($this->Tag->save($this->request->data)) {
                    $this->Flash->success(__('Your Tag has been saved.'));
                    return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
                }
                $this->Flash->error(__('Unable to add your Tag.'));
            }
        }

        public function related_post_index($id = null){
            self::checkId($id);

            // タグに関連づいてる記事を取得する。
            $tag_post = $this->Tag->find('all',array(
                'conditions' => array('id' => $id), // 検索する条件を設定する。
            ));
            // // 記事のidを全て配列に格納する。
            // $post_ids = array();
            // foreach ($tag_post[0]['Post'] as $post) {
            //     $post_ids[] = $post['id'];
            // }
            // $this->PostsTag->bindModel(array('belongsTo' => array('Post')), false);
            // // postのidをpaginateの検索条件とする。
            $tagId = $id;
            $this->paginate = array(
                'conditions' => array('PostsTag.tag_id' => $tagId,
                                        'Post.publish_flg' => parent::PUBLISH),
                'limit' => parent::POST_LIST_LIMIT,
                'joins' => array(
                    array(
                        'table' => 'posts_tags',
                        'alias' => 'PostsTag',
                        'type' => 'INNER',
                        'conditions' => array(
                            'PostsTag.tag_id' => $tagId,
                            'PostsTag.post_id = Post.id'
                        )
                    )
                ),
            );
            // $this->log($this->PostsTag->find('all', array(
            //     'conditions' => array('PostsTag.tag_id' => $id), // 検索する条件を設定する。
            //     'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
            // )));
            // 一覧表示をpaginate機能で表示させる。
            $this->set('posts', $this->paginate('Post'));

            //タイトルに使用するタグ名を設定する。
            $this->set('tag_name', $tag_post[0]['Tag']['name']);
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

            $tag = $this->Tag->findById($id);
            if (!$tag) {
                throw new NotFoundException(__('Invalid tag'));
            }
        }
    }
?>
