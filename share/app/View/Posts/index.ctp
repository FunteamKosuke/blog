<!-- File: /app/View/Posts/index.ctp -->
<legend>検索</legend>
<?php
  echo $this->Form->create('Post', array(
	'url' => array_merge(
			array(
				'action' => 'find'
			),
			$this->params['pass']
		)
	));
  echo $this->Form->input('title', array('label' => 'タイトル', 'empty' => true));
  echo $this->Form->input('category_id', array('label' => 'カテゴリ名', 'class' => 'span12', 'empty' => true));
  // echo $this->Form->input('tag_id', array('label' => 'タグ名', 'class' => 'span12', 'empty' => true));
  echo $this->Form->input('tag_id', array(
        'type' => 'select',
        'multiple' => 'true',
        'options' => $tags,
    ));
  echo $this->Form->end('検索');
?>
<h1>Blog posts</h1>
<p><?php echo $this->Html->link('Add Post', array('action' => 'add')); ?></p>
<p><?php echo $this->Html->link('Add Category', ['controller' => 'categories', 'action' => 'add']); ?></p>
<p><?php echo $this->Html->link('Add Tag', ['controller' => 'tags', 'action' => 'add']); ?></p>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Actions</th>
        <th>Category</th>
        <th>Created</th>
    </tr>

<!-- ここで $posts 配列をループして、投稿情報を表示 -->

    <?php foreach ($posts as $post): ?>
    <tr>
        <td><?php echo $post['Post']['id']; ?></td>
        <td>
            <?php
                echo $this->Html->link(
                    $post['Post']['title'],
                    array('action' => 'view', $post['Post']['id'])
                );
            ?>
        </td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $post['Post']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
            <?php
                echo $this->Html->link(
                    'Edit', array('action' => 'edit', $post['Post']['id'])
                );
            ?>
        </td>
        <td>
            <?php echo $post['Category']['name']; ?>
        </td>
        <td>
            <?php echo $post['Post']['created']; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo $this->element('bootstrap-paginate'); ?>
