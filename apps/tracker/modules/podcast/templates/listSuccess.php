<?php page_title('Podcasts') ?>
  <div class="podcasts">
  <?php foreach($podcasts as $podcast): ?>
    <h3>
        <?php echo link_to_with_icon($podcast->getTitle(), "cog", 'podcast/edit?id='.$podcast->getId()); ?>
    </h3>
    <ul>
        <li>
            &nbsp;
            <?php echo link_to_with_icon('Add episode', "add", 'episode/add?podcast_id='.$podcast->getId()); ?>
        </li>
    </ul>
  <?php endforeach; ?>
<p><?php echo link_to_with_icon('New podcast…', "add", 'podcast/add'); ?></p>
