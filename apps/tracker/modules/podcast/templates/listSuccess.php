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
        <?php $url= url_for('podcast/view?id='.$podcast->getId(),true); ?>
        <?php echo link_to_with_icon($url, "web", $url); ?>
      <?php if($sf_user->isAuthenticated()): ?>
        <div> &nbsp; 
            <?php echo link_to_with_icon('Add episode', "add", 'episode/add?podcast_id='.$podcast->getId()); ?>
        </div>
      <?php endif; ?>
      </li>
    </ul>
  <?php endforeach; ?>
  <?php if($sf_user->isAuthenticated()): ?>
    <p><?php echo link_to_with_icon('New podcast…', "add", 'podcast/add'); ?></p>
  <?php endif; ?>

<h2>Files</h2>
<ul>
  <?php if($sf_user->isAuthenticated()): ?>
    <li><?php echo link_to_with_icon('Add file…','add','#' /*'torrent/add'*/ ); // fixme when implemented ?>
  <?php endif; ?>
</li>
</ul>
