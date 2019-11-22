<nav id="header" class='navbar navbar-expand-sm navbar-info bg-info sticky-top'>
  <div class="container-fluid">
    <div class="row">
      <div id="menu" class="col-5">
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
                echo $this->Html->link(
                    __('User Index'),
                    array('controller' => 'users',
                          'action' => 'index'),
                    array('class' => 'dropdown-item')
                );
                echo $this->Html->link(
                    __('Login Twitter'),
                    array('controller' => 'users',
                          'action' => 'loginTwitter'),
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
                echo $this->Html->link(
                    '下書き一覧',
                    array('controller' => 'posts',
                          'action' => 'draftIndex'),
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
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
              お問合せ関連
            </a>
            <div class="dropdown-menu">
              <?php
                echo $this->Html->link(
                  'お問い合わせ',
                  array('controller' => 'contacts',
                        'action' => 'add')
                );
                echo $this->Html->link(
                    'お問い合わせ一覧',
                    array('controller' => 'contacts',
                          'action' => 'index'),
                    array('class' => 'dropdown-item')
                );
              ?>
            </div>
          </li>
        </ul>
    </div><!-- menu -->
      <div id="search" class="col-4">
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
            <?php echo $this->Form->input('keyword', array('class' => 'form-control search_toggle',
                                                          'empty' => true,
                                                          'label' => false,
                                                          'placeholder' => 'タグ検索')); ?>
          <label class='label-submit btn btn-primary search_toggle' for="label-search-submit">
              検索
          <?php echo $this->Form->end(array('id' => 'label-search-submit')); ?>
          </label>
      </div><!-- search -->
      <div id='login-user' class="col-3">
          <?php if ($login_user) { ?>
              <div class="container-fluid">
                  <div class="row">
                      <div id="username" class="col-8">
                          user：<?php echo h($login_user['username']); ?>
                      </div>
                      <div id="profile-icon" class="col-4">
                          <a href="/users/myPage">
                          <?php if ($profile_image = $login_user['profile_image']) {
                              $profile_image_path = '../files/user/profile_image';
                              $profile_image_path .= '/' . $login_user['profile_image_dir'];
                              $profile_image_path .= '/' . $login_user['profile_image'];
                              echo $this->Html->image($profile_image_path, array(
                                                                              'width'=>'60',
                                                                              'height'=>'60',
                                                                              'alt'=>'ログインユーザーのアイコンです。'));
                          } else {
                              echo __('My Page');
                          } ?>
                          </a>
                      </div>
                  </div>
              </div>
         <?php } ?>
      </div>
    </div><!-- .row -->
  </div><!-- contener -->
</nav>

<!-- モバイル用のヘッダー -->
<div id="mobile-header" class="navbar navbar-expand-sm navbar-info bg-info sticky-top">
    <div id="menu-icon">
        <span>menu</span>
        <?php $menu_icon_path = "../img/menu-icon.png" ?>
        <?php echo $this->Html->image($menu_icon_path,array('width'=>'50',
                                                        'height'=>'50',
                                                        'alt'=>'メニュー用のアイコンです。')); ?>
    </div>
</div>

<!-- モバイルヘッダーのメニューアイコンを押下したら表示させるもの。 -->
<div id="mobile-header-body">
    <!-- メニュー一覧 -->
    <div id="mobile-menu">
        <ul>
            <li><?php echo $this->Html->link('Home', array('controller' => 'posts',
                                                          'action' => 'index')); ?></li>
            <li>
                認証操作
                <ul>
                    <li><?php echo $this->Html->link(
                        __('User Regist'),
                        array('controller' => 'users',
                              'action' => 'add')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Login'),
                        array('controller' => 'users',
                              'action' => 'login')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Logout'),
                        array('controller' => 'users',
                              'action' => 'logout')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('User Index'),
                        array('controller' => 'users',
                              'action' => 'index')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Login Twitter'),
                        array('controller' => 'users',
                              'action' => 'loginTwitter')
                    ); ?></li>
                </ul>
            </li>
            <li>
                記事操作
                <ul>
                    <li><?php echo $this->Html->link(
                        __('Add Post'),
                        array('controller' => 'posts',
                              'action' => 'add')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Add Category'),
                        array('controller' => 'categories',
                              'action' => 'add')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Add Tag'),
                        array('controller' => 'tags',
                              'action' => 'add')
                    ); ?></li>
                    <li><?php echo $this->Html->link(
                        __('Draft Index'),
                        array('controller' => 'posts',
                              'action' => 'draftIndex')
                    ); ?></li>
                </ul>
            </li>
            <li>
                住所操作
                <ul>
                    <li><?php echo $this->Html->link(
                        __('CSV Upload'),
                        array('controller' => 'addresses',
                              'action' => 'csv_import')
                    );
                     ?></li>
                     <li><?php echo $this->Html->link(
                         __('CSV Update'),
                         array('controller' => 'addresses',
                               'action' => 'csv_update')
                     ); ?></li>
                </ul>
            </li>
            <li>
                お問合せ関連
                <ul>
                    <li><?php echo $this->Html->link(
                      __('Contact'),
                      array('controller' => 'contacts',
                            'action' => 'add')
                    );
                    ?></li>
                    <li><?php echo $this->Html->link(
                        __('Contact Index'),
                        array('controller' => 'contacts',
                              'action' => 'index')
                    ); ?></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- 検索フォーム -->
    <div id="mobile-search">
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
          <?php echo $this->Html->image($image_path,array('id' => 'mobile-search-icon',
                                                          'width'=>'50',
                                                          'height'=>'50',
                                                          'alt'=>'検索フォームのアイコンです。')); ?>
            <?php echo $this->Form->input('keyword', array('class' => 'form-control search_toggle',
                                                          'empty' => true,
                                                          'label' => false,
                                                          'placeholder' => 'タイトル検索')); ?>
          <label class='label-submit btn btn-primary search_toggle' for="label-mobile-search-submit">
              検索
          <?php echo $this->Form->end(array('id' => 'label-mobile-search-submit')); ?>
          </label>
    </div>
    <!-- ログイン情報 -->
    <div id="mobile-login-user">
        <?php if ($login_user) { ?>
            <div class="container-fluid">
                <div class="row">
                    <div id="mobile-username" class="col-8">
                        user：<?php echo h($login_user['username']); ?>
                    </div>
                    <div id="mobile-profile-icon" class="col-4">
                        <a href="/users/myPage">
                        <?php if ($profile_image = $login_user['profile_image']) {
                            $profile_image_path = '../files/user/profile_image';
                            $profile_image_path .= '/' . $login_user['profile_image_dir'];
                            $profile_image_path .= '/' . $login_user['profile_image'];
                            echo $this->Html->image($profile_image_path, array(
                                                                            'width'=>'60',
                                                                            'height'=>'60',
                                                                            'alt'=>'ログインユーザーのアイコンです。'));
                        } else {
                            echo __('My Page');
                        } ?>
                        </a>
                    </div>
                </div>
            </div>
       <?php } ?>
    </div>
    <div id="mobile-close">
        <label class="btn btn-outline-primary">
            <?php echo __('Close Menu'); ?>
        </label>
    </div>
</div>
