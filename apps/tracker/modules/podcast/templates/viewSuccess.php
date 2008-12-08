<h2><?php echo $podcast->getTitle() ?></h2>

<?php if($sf_user->isAuthenticated()): ?>
<form action="<?php echo url_for('podcast/update') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php echo $form ?>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="submit" value="Save"/>
      </td>
      <td>
        <input type="submit" value="Remove"/><?php /* todo fixme */ ?>
      </td>
    </tr>
  </table>
</form>
<?php endif; ?>

<h3>Feeds</h3>
<?php if($feeds): ?>
<ul>
  <li>
  <?php
    $url = url_for($sf_context->getRouting()->getCurrentInternalUri(), true);
    echo link_to_with_icon($url, 'web', $url);
  ?>
    
  </li>
  <?php foreach($feeds as $feed): ?>
    <li>
      <?php 
        $url = url_for('feed/feed?id='.$feed->getId(),true); // make this use a slug todo
        echo link_to_with_icon($url, 'rss', $url);
      ?>
    </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
  <p><i>No feeds yet.</i></p>
<?php endif; ?>

<h3>Episodes</h3>
<ul>
  <?php
    if($sf_user->isAuthenticated())
    {  
      if(true) // TODO add !Podcast->isFeedBased() here
      {
        echo '<li>',link_to_with_icon('Add episode…','add','episode/add?podcast_id='.$podcast->getId()),'</li>';
      }
    }
  ?>
  <?php if($episodes): ?>
      <?php foreach($episodes as $episode): ?>
        <li>
          <?php echo link_to_with_icon($episode->getCreatedAt('Y M d').' - '.$episode->getTitle(),'episode','episode/view?id='.$episode->getId()) ?>
        </li>
      <?php endforeach; ?>
  <?php else: ?>
    <p><i>No episodes yet.</i></p>
  <?php endif; ?>
</ul>
<?php slot('feed');
foreach($feeds as $feed) 
{
  echo auto_discovery_link_tag ('rss','feed/feed?id='.$feed->getId()); // make this use a slug todo
}
end_slot();
?>
