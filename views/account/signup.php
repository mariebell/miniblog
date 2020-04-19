<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<h2>アカウント登録</h2>

<form action="<?= $base_url ?>/account/register" method ="post">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">

  <?php if (isset($errors) && count($errors) > 0){ ?>
  <ul class="error_list">
    <?php foreach ($errors as $error) { ?>
      <li><?= $this->escape($error); ?></li>
    <?php } ?>
  </ul>
  <?php } ?>

<?= $this->render('account/inputs', ['user_name' => $user_name, 'password' => $password]) ?>

  <p>
    <input type="submit" value="登録">
  </p>
</form>