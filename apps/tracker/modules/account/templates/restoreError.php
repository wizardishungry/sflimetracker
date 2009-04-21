<?php echo $form; ?>
<?php if ($form->hasGlobalErrors()): ?>
          <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
            <li><?php echo $name.': '.$error ?></li>
          <?php endforeach; ?>
<?php endif; ?>
