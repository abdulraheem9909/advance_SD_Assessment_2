<?php
namespace App\Controller;

use App\Entity\Adverts;
use App\Form\AdvertsType;
use App\Repository\AdvertsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adverts')]
class AdvertsController extends AbstractController
{

    // Show all adverts (public access)
    #[Route('/', name: 'app_adverts_index', methods: ['GET'])]
    public function index(AdvertsRepository $advertsRepository): Response
    {
        $adverts = $advertsRepository->findAll();
        return $this->render('adverts/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    #[Route('/my-adverts', name: 'app_user_adverts', methods: ['GET'])]
    public function userAdverts(AdvertsRepository $advertsRepository): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Fetch the adverts associated with this user
        $adverts = $advertsRepository->findBy(['user' => $user]);

        return $this->render('adverts/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    // New advert (only accessible by logged-in users)
    #[Route('/new', name: 'app_adverts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advert = new Adverts();
        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if ($user) {
                $advert->setUser($user); // Set the current user as the advert's creator
            } else {
                $this->addFlash('error', 'You must be logged in to create an advert.');
                return $this->render('adverts/new.html.twig', [
                    'advert' => $advert,
                    'form' => $form,
                ]);
            }

            // Handle file upload for advert image (if any)
            $file = $form->get('image')->getData();
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory'), $filename);
                $advert->setImage($filename);
            }

            $entityManager->persist($advert);
            $entityManager->flush();

            return $this->redirectToRoute('app_adverts_index');
        }

        return $this->render('adverts/new.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }

    // Edit advert (only the creator or moderators can edit)
    #[Route('/{id}/edit', name: 'app_adverts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adverts $advert, EntityManagerInterface $entityManager): Response
    {
        // Check if the current user is the creator or a moderator
        if ($advert->getUser() !== $this->getUser() && !in_array('ROLE_MODERATOR', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'You are not authorized to edit this advert.');
            return $this->redirectToRoute('app_adverts_index');
        }

        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_adverts_index');
        }

        return $this->render('adverts/edit.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }

    // Delete advert (only the creator or moderators can delete)
    #[Route('/{id}/delete', name: 'app_adverts_delete', methods: ['POST'])]
    public function delete(Request $request, Adverts $advert, EntityManagerInterface $entityManager): Response
    {
        // Check if the current user is the creator or a moderator
        if ($advert->getUser() !== $this->getUser() && !in_array('ROLE_MODERATOR', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'You are not authorized to delete this advert.');
            return $this->redirectToRoute('app_adverts_index');
        }

        if ($this->isCsrfTokenValid('delete' . $advert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($advert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adverts_index');
    }
}
