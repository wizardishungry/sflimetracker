<h1><?php echo $podcast->getTitle() ?></h1>

<h2>Feeds</h2>
<?php if($feeds): ?>
<ul>
  <li>
  <?php
    $url = url_for($podcast->getUri(), true);
    echo link_to_with_icon($url, 'web', $url);
  ?> (All feeds)
    
  </li>
  <?php foreach($feeds as $feed) foreach($feed->getUris() as $uri): ?>
    <li>
      <?php 
        $url = url_for($uri,true);
        echo link_to_with_icon($url, 'rss', $url);
      ?>
    </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
  <p><i>No feeds yet.</i></p>
<?php endif; ?>


<h2>Episodes</h2>
  <?php if($episodes): ?>
    <ul>
      <?php foreach($episodes as $episode): ?>
        <li>
          <?php echo link_to_with_icon($episode->getCreatedAt('Y M d').
            ' - '.$episode->getTitle(),'episode', $episode->getUri()) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p><i>No episodes yet.</i></p>
  <?php endif; ?>
<?php slot('feed');
foreach($feeds as $feed)  foreach($feed->getUris() as $format => $uri) 
{
  echo auto_discovery_link_tag ('rss',$uri,Array(
    'title'=>($feed->getTags()=='default'?'':$feed->getTags().' ').'via '.$format,
  ));
}
end_slot();
?>
