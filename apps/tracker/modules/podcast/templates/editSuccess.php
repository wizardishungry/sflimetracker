<h1><?php echo link_to($podcast->getTitle(),$podcast->getUri()) ?></h1>

<form action="<?php echo url_for('podcast/edit') ?>" method="POST" enctype="multipart/form-data">
<table>
    <?php echo $form ?>
    <tr>
    <td>&nbsp;</td>
    <td>
        <input type="submit" value="Save"/>
    </td>
    <td>

    </td>
    </tr>
</table>
</form>
<?php echo delete_form_for_object($podcast); ?>

<h2>Formats</h2>
<?php if($feeds): ?>
<ul>
  <?php foreach($feeds as $feed): ?>
    <li>
      <?php 
        echo $feed->getTags();
      ?>
    </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
  <p><i>No feeds yet.</i></p>
<?php endif; ?>


<h3>Add a new format for feeds</h3>

<p>These feeds generally correspond to a media format like "mp3 high quality".</p>
<form action="<?php echo url_for('podcast_feed/add') ?>" method="POST">
    <table>
    <?php echo $podcast_feed_form; ?>
    <tr>
        <td>&nbsp;</td>
        <td>
        <input type="submit" value="Add"/>
        </td>
        <td>

        </td>
    </tr>
    </table>
</form>

<h2>Episodes</h2>
<ul>
  <?php if($episodes): ?>
      <?php foreach($episodes as $episode): ?>
        <li>
          <?php echo link_to_with_icon($episode->getCreatedAt('Y M d').
            ' - '.$episode->getTitle(),'episode', $episode->getUri()) ?>
        </li>
      <?php endforeach; ?>
  <?php else: ?>
    <p><i>No episodes yet.</i></p>
  <?php endif; ?>
  <?php
    if(true) // TODO add !Podcast->isFeedBased() here
    {
    echo '<li>',link_to_with_icon('Add episode…','add','episode/add?podcast_id='.$podcast->getId()),'</li>';
    }
  ?>
</ul>
