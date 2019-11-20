<?php
  class ImagesController extends AppController {

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function upload(){
      // 関連づけたい記事のidを渡す。
      $this->set('post_id', Hash::get($this->request->query, "post_id"));
      // アップロード後に推移したいViewを渡す。
      $this->set('redirect_view', Hash::get($this->request->query, "redirect_view"));
      if ($this->request->is('post')){
          $this->log($this->request->data);
        $save_data = array();
        // 保存するための形式で配列を作成する。
        foreach ($this->request->data['Image']['files'] as $file) {
          $data['Post']['id'] = $this->request->data['Post']['post_id'];
          $data['Image']['image'] = $file;
          $save_data[] = $data;
        }
        $this->log($save_data);
        if($this->Image->saveAll($save_data, array('deep' => true))){
          $this->Flash->success(__('The image was uploaded successfully.'));
          return $this->redirect(array('controller' => 'Posts',
                                        'action' => $this->request->data['Post']['redirect_view'],
                                        $this->request->data['Post']['post_id']));
        } else {
          $this->Flash->error(__('Image upload failed.'));
        }
      }
    }

    // 画像を削除する。
    public function delete($id = null){
      if ($this->request->is('get')) {
          throw new MethodNotAllowedException();
      }

      self::checkId($id);

      if ($this->Image->delete($id)) {
          $this->Flash->success(
              __('The image with id: %s has been deleted.', h($id))
          );
      } else {
          $this->Flash->error(
              __('The image with id: %s could not be deleted.', h($id))
          );
      }
      return $this->redirect(array('controller' => 'posts',
                                    'action' => Hash::get($this->request->query, "redirect_view"),
                                    Hash::get($this->request->query, "post_id")));
    }

    // 画像を差し替える。
    public function edit($id = null){
      self::checkId($id);

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
            $this->Flash->success(__('Successfully replaced the image.'));
            return $this->redirect(array('controller' => 'posts',
                                          'action' => Hash::get($this->request->query, "redirect_view"),
                                          Hash::get($this->request->query, "post_id")));
        }
        $this->Flash->error(__('Failed to replace image.'));
      }

      if (!$this->request->data) {
          $this->request->data = $image;
      }
    }

    private function checkId($id){
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
    }
  }
?>
