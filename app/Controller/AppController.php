<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    const ID_MAX = 2147483647;
    const POST_LIST_LIMIT = 4;
    const RELATED_POST_LIST_LIMIT = 3;
    const POPULAR_POST_LIMIT = 5;
    const NEW_POST_LIMIT = 5;
    const ATTENTION_POST_LIMIT = 5;
    const PUBLISH = 1; //公開を表す
    const NO_PUBLISH = 0; // 非公開を表す

    public $uses = array('Post', 'Category', 'Tag');

    public $components = array(
      'Session',
      'Flash',
      'MySecurity',
      // 'Security' => array(
      //               'csrfExpires' => '+1 hour'
      //               ),
      'Auth' => array(
          'loginRedirect' => array(
              'controller' => 'posts',
              'action' => 'index'
          ),
          'logoutRedirect' => array(
              'controller' => 'posts',
              'action' => 'index',
              'home'
          ),
          'authenticate' => array(
              'Form' => array(
                  'passwordHasher' => 'Blowfish'
              )
          ),
          'authorize' => array('Controller')
      )
    );

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // デフォルトは拒否
        return false;
    }

    public function beforeFilter() {
        // 記事一覧の追加と削除の操作の有無をユーザー情報によって判断するためにセットする。
        $this->set('login_user', $this->Auth->user());

        // サイドバーに表示する人気記事を取得する。
        $side_popular_posts = $this->Post->find('all', array(
                                                    'conditions' => array('publish_flg' => self::PUBLISH),
                                                    'limit' => self::POPULAR_POST_LIMIT,
                                                    'order' => array('Post.access DESC')));
        $this->set('side_popular_posts', $side_popular_posts);

        // サイドバーに表示する新着記事を取得する。
        $side_new_posts = $this->Post->find('all', array(
                                                    'conditions' => array('publish_flg' => self::PUBLISH),
                                                    'limit' => self::NEW_POST_LIMIT,
                                                    'order' => array('Post.id DESC')));
        $this->set('side_new_posts', $side_new_posts);

        // 注目記事を取得する。
        // 注目記事は新着記事順に大きい数字を表し、その数字をアクセス数に乗算して、数値を出し、その数値が大きい順に注目記事とする。
        $posts = $this->Post->find('all', array('conditions' => array('publish_flg' => self::PUBLISH)));

        // 注目を表す数値を計算する。
        $posts_count = count($posts);
        $posts_attention_number = array();
        foreach ($posts as $key => $post) {
          $posts_attention_number[$key] = ($posts_count - $key) * $post['Post']['access'];
        }

        // valueの大きい順に並び替える
        arsort($posts_attention_number);

        $count = 0;
        $count_max = self::ATTENTION_POST_LIMIT;
        // 記事の数がATTENTION_POST_LIMITなら取得できた件数を表示する数とする。
        if($posts_count < self::ATTENTION_POST_LIMIT){
          $count_max = $posts_count;
        }
        // 並び替えた先頭の記事から指定した数だけ取得する。
        $side_attention_posts = array();
        foreach ($posts_attention_number as $key => $value) {
          $side_attention_posts[] = $posts[$key];
          if (++$count === $count_max) {
              break;
          }
        }

        $this->set('side_attention_posts', $side_attention_posts);

        /***footer関連***/
        // フッターに表示するカテゴリ
        $footer_categories = $this->Category->find('all');
        $this->set('footer_categories', $footer_categories);

        // フッターに表示するタグ
        $footer_tags = $this->Tag->find('all');
        $this->set('footer_tags', $footer_tags);
    }
}
