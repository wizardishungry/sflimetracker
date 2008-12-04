<h3>Podcasts</h3>
<ul>
  <?php foreach($podcasts as $podcast): ?>
    <li>
      <?php echo
        link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId()), ' ',
        link_to('RSS','feed/feed?id='.$podcast->getId());
      ?>
    </li>
  <?php endforeach; ?>
  <li><?php echo link_to('New Podcast…','podcast/add') ?></li>
</ul>

<h3>Files</h3>
<ul>
  <li><?php echo link_to('Add File…','podcast/add') ?>
</li>
</ul>
