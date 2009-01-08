<h1><?php echo $podcast->getTitle() ?></h1>

<?php if($sf_user->isAuthenticated()): ?>
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
<?php endif; ?>

<h2>Feeds</h2>
<?php if($feeds): ?>
<ul>
  <li>
  <?php
    $url = url_for($podcast->getUri(), true);
    echo link_to_with_icon($url, 'web', $url);
  ?> (All feeds)
    
  </li>
  <?php foreach($feeds as $feed): ?>
    <li>
      <?php 
        $url = url_for($feed->getUri(),true); // make this use a slug todo
        echo link_to_with_icon($url, 'rss', $url);
      ?>
    </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
  <p><i>No feeds yet.</i></p>
<?php endif; ?>

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
    if($sf_user->isAuthenticated())
    {  
      if(true) // TODO add !Podcast->isFeedBased() here
      {
        echo '<li>',link_to_with_icon('Add episode…','add','episode/add?podcast_id='.$podcast->getId()),'</li>';
      }
    }
  ?>
</ul>
<?php slot('feed');
foreach($feeds as $feed) 
{
  echo auto_discovery_link_tag ('rss','feed/feed?slug='.$feed->getSlug()); // make this use a slug todo
}
end_slot();
?>
