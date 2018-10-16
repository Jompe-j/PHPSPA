<?php

namespace view;

use model\CookieSettingsModel;

class LayoutView {

    private $loginConstants;
    private $message = '';
    private $content;
    private $isLoggedIn = false;
    private $cookieToken;
    private $cookieExpiration;

    public function __construct() {
        $this->loginConstants = new \model\LoginConstants();
    }


    public function render(IContentView $contentView, DateTimeView $dateTimeView): void {
        $this->content = $contentView;
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 3</h1><br>
          ' . $this->renderRegisterLink(false, $this->isLoggedIn /* TODO Should not be hardcoded.*/) . '

          ' . $this->renderIsLoggedIn($this->isLoggedIn) . '
          
          <div class="container">
              ' . $this->renderContent() . '
              ' . $this->renderLogoutButton() . '
              ' . $dateTimeView->show() . '
          </div>
         </body>
      </html>
    ';
  }

  public function userTryToLogin(): bool
  {
        return isset($_POST[$this->loginConstants::getLogin()]);
  }

  public function setIsLoggedInStatus(bool $status): void {
        $this->isLoggedIn = $status;
        if($this->isLoggedIn){
            $this->message = 'Welcome';
        }
  }

    private function renderRegisterLink($isRegistering, $isLoggedIn): string
    {
        if ($isLoggedIn){
            return '';
        }
        if ($isRegistering){
            return '<a href="?toLogin">Back to login</a>';
        }

            return  '<a href="?' . \model\LoginConstants::getToRegister() . '">Register a new user</a>'; //'<a href="?registerUser">Register a new user</a>';


    }

    private function renderIsLoggedIn($isLoggedIn): string {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        }

        return '<h2>Not logged in</h2>
              <br><br>';
    }

    private function renderContent() : string
    {
        return $this->content->contentToString();
    }

    private function renderLogoutButton(): string {
        if($this->isLoggedIn){
            return '
			<form  method="post" >
				<p id="' . $this->loginConstants::getMessageId() . '">' . $this->message .'</p>
				<input type="submit" name="' . $this->loginConstants::getLogout() . '" value="logout"/>
			</form>
		';
        }
        return '';

    }

    public function isLoggingOut(): bool {
            return isset($_POST[$this->loginConstants::getLogout()]);
    }

    public function logOut(): void {
        setcookie($this->loginConstants::getCookieName(), null, -1);
        setcookie($this->loginConstants::getCookiePassword(), null, -1);
        unset($_COOKIE[$this->loginConstants::getCookieName()], $_COOKIE[$this->loginConstants::getCookiePassword()]);
        $this->setIsLoggedInStatus(false);
    }



    public function getToken() {
        return $this->cookieToken;
    }

    public function getExpiration() {
        return $this->cookieExpiration;
    }

    public function setMessageCode(int $code) {
        if($code === 11){
            $this->message = 'Welcome';
        }

        if($code === 12){
            $this->message = 'Welcome and you will be remembered';
        }

        if($code === 13){
            $this->message = 'Welcome back with cookie';
        }
    }


}
