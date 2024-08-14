<?php


namespace App\Traits;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Get the post logout redirect path.
     *
     * @return string
     */
    public function loggedOutRedirectPath()
    {
        if (method_exists($this, 'loggedOutRedirectTo')) {
            return $this->loggedOutRedirectTo();
        }

        return property_exists($this, 'loggedOutRedirectTo') ? $this->loggedOutRedirectTo : '/';
    }
}
