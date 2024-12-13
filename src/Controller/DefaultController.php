<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    #[Route('/', name: 'homepage_redirect')]
    public function redirectToHome(): RedirectResponse
    {
        return new RedirectResponse('/adverts');
    }


}
