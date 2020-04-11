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

  <table>
  <tbody>
    <tr>
    <th>ユーザID</th>
    <td><input type="text" name="user_name" value="" /></td>
    </tr>
    <tr>
    <th>パスワード</th>
    <td>
      <input type="password" name="password" value="" />
    </td>
    </tr>
  </tbody>
  </table>

  <p>
    <input type="submit" value="登録">
  </p>
</form>