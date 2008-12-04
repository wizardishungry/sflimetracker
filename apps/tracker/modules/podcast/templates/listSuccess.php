<h3>Podcasts</h3>
<ul>
  <?php foreach($podcasts as $podcast): ?>
    <li>
      <?php echo
        link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId());
      ?>
      <ul>
        <li>[web] <?php 
          $url=url_for('podcast/view?id='.$podcast->getId(),true);
          echo link_to($url,$url);
        ?></li>
        <li>[add] <?php 
          echo link_to('Add…','episode/add?podcast_id='.$podcast->getId());
        ?></li>
      </ul>
    </li>
  <?php endforeach; ?>
  <li><?php echo link_to('New Podcast…','podcast/add') ?></li>
</ul>

<h3>Files</h3>
<ul>
  <li><?php echo link_to('Add File…','podcast/add') ?>
</li>
</ul>
