<ul>
<?php foreach($podcasts as $podcast): ?>
  <li>
    <?php
      echo link_to($podcast->getTitle(),'podcast/view?id='.$podcast->getId());
    ?>
  </li>
<?php endforeach; ?>
</ul>
