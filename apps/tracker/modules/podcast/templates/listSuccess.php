<h2>Podcasts</h2>
<ul>
  <?php foreach($podcasts as $podcast): ?>
    <li>
      <?php echo
        link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId());
      ?>
      <ul>
        <li>
          <?php $url= url_for('podcast/view?id='.$podcast->getId(),true); ?>
          <?php echo link_to_with_icon($url, "web", $url); ?>
        </li>
        <li>
          <?php echo link_to_with_icon('Add…', "add", 'episode/add?podcast_id='.$podcast->getId()); ?>
        </li>
      </ul>
    </li>
  <?php endforeach; ?>
  <li><?php echo link_to('New Podcast…','podcast/add') ?></li>
</ul>

<h2>Files</h2>
<ul>
  <li><?php echo link_to('Add File…','podcast/add') ?>
</li>
</ul>
