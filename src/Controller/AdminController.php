<?php

namespace App\Controller;

use App\Entity\Adverts;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users_list')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        // Ensure only admins can access this route
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Get the currently logged-in user
        $currentUser = $this->getUser();

        // Get all users, excluding the currently logged-in user
        $users = $entityManager->getRepository(Users::class)
            ->createQueryBuilder('u')
            ->where('u.id != :currentUserId')
            ->setParameter('currentUserId', $currentUser->getId())
            ->getQuery()
            ->getResult();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}/toggle-role', name: 'admin_toggle_user_role')]
    public function toggleUserRole(int $id, EntityManagerInterface $entityManager): Response
    {
        // Ensure only admins can access this route
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Find the user by ID
        $user = $entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('admin_users_list');
        }

        // Toggle role between ROLE_USER and ROLE_MODERATOR
        if (in_array('ROLE_MODERATOR', $user->getRoles())) {
            // Remove ROLE_MODERATOR and set ROLE_USER
            $user->setRoles(['ROLE_USER']);
            $this->addFlash('success', 'User role changed to User');
        } else {
            // Add ROLE_MODERATOR and remove ROLE_USER
            $user->setRoles(['ROLE_MODERATOR']);
            $this->addFlash('success', 'User role changed to Moderator');
        }

        $entityManager->flush();

        return $this->redirectToRoute('admin_users_list');
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_delete_user')]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Find the user to be deleted
        $user = $entityManager->getRepository(Users::class)->find($id);
        if (!$user) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('admin_users_list');
        }

        // Manually delete all adverts related to this user
        $adverts = $entityManager->getRepository(Adverts::class)->findBy(['user' => $user]);
        foreach ($adverts as $advert) {
            $entityManager->remove($advert);
        }

        // Delete the user
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User and their adverts deleted successfully');
        return $this->redirectToRoute('admin_users_list');
    }
}
