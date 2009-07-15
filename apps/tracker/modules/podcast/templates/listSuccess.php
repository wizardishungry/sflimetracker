<div class="podcasts">
<?php foreach($podcasts as $podcast): ?>
  <div class="podcast">
    <?php if($podcast->getImageUrl()) { ?>
      <?php echo link_to(image_tag($podcast->getImageUrl(), array('class' => 'cover')), 'podcast/edit?id='.$podcast->getId()); ?>
    <?php } else { ?>
      <img class="cover" />
    <?php } ?>
    <h3>
      <?php echo link_to($podcast->getTitle(), 'podcast/edit?id='.$podcast->getId()); ?>
    </h3>
    <?php echo link_to_with_icon('Add episode', "add", 'episode/add?podcast_id='.$podcast->getId()); ?>
  </div>
<?php endforeach; ?>

<p><?php echo link_to_with_icon('New podcast', "add", 'podcast/add'); ?></p>
