<?php
  class ImagesController extends AppController {
    public function upload(){
      // 関連づけたい記事のidを渡す。
      $this->set('post_id', Hash::get($this->request->query, "post_id"));
      if ($this->request->is('post')){
          $this->log($this->request->data);
        $save_data = array();
        // 保存するための形式で配列を作成する。
        foreach ($this->request->data['Image']['files'] as $file) {
          $data['Post']['id'] = $this->request->data['Post']['post_id'];
          $data['Image']['image'] = $file;
          $save_data[] = $data;
        }

        if($this->Image->saveAll($save_data, array('deep' => true))){
          $this->Flash->success(__('画像のアップロードに成功しました。'));
          return $this->redirect(array('controller' => 'Posts',
                                        'action' => 'view',
                                        $this->request->data['Post']['post_id']));
        } else {
          $this->Flash->error(__('画像のアップロードに失敗しました。'));
        }
      }
    }

    // 画像を削除する。
    public function delete($id){
      if ($this->request->is('get')) {
          throw new MethodNotAllowedException();
      }

      if (!$id) {
          throw new NotFoundException(__('Invalid image'));
      }
      // 数値以外なら
      if (!is_numeric($id)) {
          throw new NotFoundException(__('Invalid image'));
      }

      // idで表現できる最大値を超えていないか
      if (parent::ID_MAX < $id) {
          throw new NotFoundException(__('Invalid image'));
      }

      $image = $this->Image->findById($id);
      if (!$image) {
          throw new NotFoundException(__('Invalid image'));
      }

      if ($this->Image->delete($id)) {
          $this->Flash->success(
              __('image id: %s の削除に成功しました。', h($id))
          );
      } else {
          $this->Flash->error(
              __('image id: %s の削除に失敗しました。', h($id))
          );
      }
      return $this->redirect(array('controller' => 'posts',
                                    'action' => 'view',
                                    Hash::get($this->request->query, "post_id")));
    }

    // 画像を差し替える。
    public function edit($id = null){
      if (!$id) {
          throw new NotFoundException(__('Invalid image'));
      }
      // 数値以外なら
      if (!is_numeric($id)) {
          throw new NotFoundException(__('Invalid image'));
      }

      // idで表現できる最大値を超えていないか
      if (parent::ID_MAX < $id) {
          throw new NotFoundException(__('Invalid image'));
      }

      $image = $this->Image->findById($id);
      if (!$image) {
          throw new NotFoundException(__('Invalid image'));
      }

      if ($this->request->is(array('post', 'put'))) {
        // ただ保存し直すだけだと画像データが残ったままなので、先に削除する。データはsaveする前に取得し、パスを作成しておく。
        $image = $this->Image->findById($id);
        $image_path = 'files/image/image';
        $image_path .= '/' . $image['Image']['image_dir'];
        $image_path .= '/' . $image['Image']['image'];

        // 画像を保存する
        $this->Image->id = $id;
        if ($this->Image->save($this->request->data)) {
            chmod($image_path, 0777); //保存に成功したら前の画像を削除する。
            unlink($image_path);
            $this->Flash->success(__('画像の差し替えに成功しました。'));
            return $this->redirect(array('controller' => 'posts',
                                          'action' => 'view',
                                          Hash::get($this->request->query,
                                          "post_id")));
        }
        $this->Flash->error(__('画像の差し替えに失敗しました。'));
      }

      if (!$this->request->data) {
          $this->request->data = $image;
      }
    }
  }
?>
