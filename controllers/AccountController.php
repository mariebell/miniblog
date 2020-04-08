<?php

class AccountController extends Controller
{
  public function signupAction()
  {
    $auth_action = true;
    return $this->render([
      '_token' => $this->generateCsrfToken('accounts/signup'),
    ]);
  }
}