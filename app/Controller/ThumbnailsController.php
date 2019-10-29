<?php
  class ThumbnailsController extends AppController {
      // 画像を差し替える。
      public function edit($id = null){

        self::checkId($id);

        if ($this->request->is(array('post', 'put'))) {
          // ただ保存し直すだけだと画像データが残ったままなので、先に削除する。データはsaveする前に取得し、パスを作成しておく。
          $thumbnail = $this->Thumbnail->findById($id);
          $thumbnail_path = 'files/thumbnail/thumbnail';
          $thumbnail_path .= '/' . $thumbnail['Thumbnail']['thumbnail_dir'];
          $thumbnail_path .= '/' . $thumbnail['Thumbnail']['thumbnail'];

          // 画像を保存する
          $this->Thumbnail->id = $id;
          if ($this->Thumbnail->save($this->request->data)) {
              chmod($thumbnail_path, 0777); //保存に成功したら前の画像を削除する。
              unlink($thumbnail_path);
              $this->Flash->success(__('Successfully replaced the thumbnail.'));
              return $this->redirect(array('controller' => 'posts',
                                            'action' => Hash::get($this->request->query, "redirect_view"),
                                            Hash::get($this->request->query, "post_id")));
          }
          $this->Flash->error(__('Failed to replace thumbnail.'));
        }

        if (!$this->request->data) {
            $this->request->data = $thumbnail;
        }
      }

      private function checkId($id){
          if (!$id) {
              throw new NotFoundException(__('Invalid thumbnail'));
          }
          // 数値以外なら
          if (!is_numeric($id)) {
              throw new NotFoundException(__('Invalid thumbnail'));
          }

          // idで表現できる最大値を超えていないか
          if (parent::ID_MAX < $id) {
              throw new NotFoundException(__('Invalid thumbnail'));
          }

          $thumbnail = $this->Thumbnail->findById($id);
          if (!$thumbnail) {
              throw new NotFoundException(__('Invalid thumbnail'));
          }
      }
  }

?>
