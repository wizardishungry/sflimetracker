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
</ul>
<h3>Files</h3>
<ul>
  <li>TODO</li>
</ul>
