<nav id="header" class='navbar navbar-expand-sm navbar-info bg-info sticky-top'>
  <div class="container-fluid">
    <div class="row blog-middle">
      <div id="menu" class="col-7">
        <ul class="navbar-nav">
          <li class="nav-item"><?php echo $this->Html->link('Home', array('controller' => 'posts',
                                                        'action' => 'index',
                                                        'class' => 'nav-link')); ?></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
              認証操作
            </a>
            <div class="dropdown-menu">
              <?php
                echo $this->Html->link(
                    'ユーザー登録',
                    array('controller' => 'users',
                          'action' => 'add'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    'ログイン',
                    array('controller' => 'users',
                          'action' => 'login'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    'ログアウト',
                    array('controller' => 'users',
                          'action' => 'logout'),
                    array('class' => 'dropdown-item')
                );
              ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
              記事操作
            </a>
            <div class="dropdown-menu">
              <?php
                echo $this->Html->link(
                    '記事追加',
                    array('controller' => 'posts',
                          'action' => 'add'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    'カテゴリ追加',
                    array('controller' => 'categories',
                          'action' => 'add'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    'タグ追加',
                    array('controller' => 'tags',
                          'action' => 'add'),
                    array('class' => 'dropdown-item')
                );
              ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
              住所操作
            </a>
            <div class="dropdown-menu">
              <?php
                echo $this->Html->link(
                    'csvファイルアップロード',
                    array('controller' => 'addresses',
                          'action' => 'csv_import'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    'csvファイルアップデート',
                    array('controller' => 'addresses',
                          'action' => 'csv_update'),
                    array('class' => 'dropdown-item')
                );
                // echo $this->Html->link(
                //     'csvインポート',
                //     array('controller' => 'zipcodes',
                //           'action' => 'csv_import'),
                //     array('class' => 'dropdown-item')
                // );
                // echo $this->Html->link(
                //     '住所情報',
                //     array('controller' => 'zipcodes',
                //           'action' => 'zip_info'),
                //     array('class' => 'dropdown-item')
                // );
              ?>
            </div>
          </li>
        </ul>
    </div><!-- menu -->
      <div id="search" class="col-5">
        <?php echo $this->Form->create('Post', array(
        'url' => array_merge(
            array(
              'controller' => 'posts',
              'action' => 'find',
            ),
            $this->params['pass']
          ),
        'class' => 'form-inline'
        )); ?>
          <?php $image_path = "../img/search-icon.png" ?>
          <?php echo $this->Html->image($image_path,array('id' => 'icon',
                                                          'width'=>'50',
                                                          'height'=>'50',
                                                          'alt'=>'検索フォームのアイコンです。')); ?>
            <?php echo $this->Form->input('title', array('class' => 'form-control search_toggle',
                                                          'empty' => true,
                                                          'label' => false,
                                                          'placeholder' => '記事のタイトル')); ?>
          <label class='label-submit btn btn-primary search_toggle' for="label-search-submit">
              検索
          <?php echo $this->Form->end(array('id' => 'label-search-submit')); ?>
          </label>
      </div><!-- search -->
    </div><!-- .row -->
  </div><!-- contener -->
</nav>
