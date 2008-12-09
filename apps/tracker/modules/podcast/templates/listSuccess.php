<h2>Podcasts</h2>
  <div class="podcasts">
  <?php foreach($podcasts as $podcast): ?>
    <h3>
      <?php echo
        link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId());
      ?>
    </h3>
    <ul>
      <li>
        <?php $feeds = $podcast->getFeeds() ?>
        <?php  if($feeds[1]): ?>
	  <?php $url= url_for('podcast/view?id='.$podcast->getId(),true); ?>
          <?php echo link_to_with_icon($url, "web", $url); ?> (All feeds)
        <?php else: ?>
          <?php $url= url_for('feed/feed?id='.$feeds[0]->getId(),true); ?>
          <?php echo link_to_with_icon($url, "rss", $url); ?>
        <?php endif; ?>
      </li>
      <li>
        <?php echo link_to_with_icon('Add episode', "add", 'episode/add?podcast_id='.$podcast->getId()); ?>
      </li>
    </ul>
  <?php endforeach; ?>
  <p><?php echo link_to_with_icon('New podcast…', "add", 'podcast/add'); ?></p>

<h2>Files</h2>
<ul>
  <li><?php echo link_to_with_icon('Add file…','add','podcast/add'); ?>
</li>
</ul>
