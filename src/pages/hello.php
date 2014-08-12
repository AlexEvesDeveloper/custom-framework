<?php $input = $request->get('name', 'World'); ?>

Hello <?php print htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); ?>