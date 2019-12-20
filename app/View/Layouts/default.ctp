<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		echo $this->Html->meta('icon');


		// css読み込み
		include('css-read.ctp');
		// js読み込み
		include('js-read.ctp');

		echo $this->fetch('meta');
		echo $this->fetch('css');
?>
</head>
<body>
	<div id="container">
		<?php include('header.ctp') ?>
		<?php include('content.ctp') ?>
		<!-- 画面topにスクロールするためのボタンを設置する。 -->
		<div id="scroll-top-move">
			<?php $icon_path = "../img/scroll-top-move-icon.png" ?>
			<?php echo $this->Html->image($icon_path,array('id' => 'scroll-top-move-icon',
															'width'=>'70',
															'height'=>'70',
															'alt'=>'画面topにスクロールすためのアイコンです。')); ?>
		</div>
		<?php include('footer.ctp') ?>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
	<?php //echo $this->fetch('script'); ?>
</body>
</html>
