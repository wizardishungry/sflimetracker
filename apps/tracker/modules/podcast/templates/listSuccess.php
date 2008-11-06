<h3>Feeds</h3>
<ul>
<?php foreach($podcasts as $podcast): ?>
  <li>
    <?php echo
      link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId()), ' ',
      link_to('RSS','feed/feed?id='.$podcast->getId());
    ?>
  </li>
<?php endforeach; ?>
<?php echo link_to('Add…','podcast/add') ?>
</ul>
<h3>Files</h3>
<ul>
  <li>TODO</li>
</ul>

<h3>Settings</h3>
<ul>
  <li><?php echo link_to('Change password','account/password') ?></li>
  <li>todo add more
</ul>
