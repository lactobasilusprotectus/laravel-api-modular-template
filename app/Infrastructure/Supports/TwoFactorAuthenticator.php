<?php

namespace App\Infrastructure\Supports;

use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorAuthenticator extends Authenticator
{
    protected function canPassWithoutCheckingOTP(): bool
    {
       return !$this->getUser()->google2fa_enable ||
           $this->isEnabled() ||
           $this->noUserIsAuthenticated() ||
           $this->twoFactorAuthStillValid();
    }

    protected function getGoogle2FASecretKey()
    {
        return $this->getUser()->{$this->config('config.otp_secret_column')};
    }
}
