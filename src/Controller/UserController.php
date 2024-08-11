<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user != null) {
            return $this->json(['message' => 'Пользователь с таким email уже существует'], 409);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($hasher->hashPassword($user, $password));
        $user->setRoles([]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'Регистрация успешна!']);
    }
}
