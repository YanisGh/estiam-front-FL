<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

class HomeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_home_default')]
    #[Route('/home', name: 'app_home')]
    public function index(Security $security): Response
    {
        //die("test");
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home/index.html.twig');
        }else{
            return $this->redirectToRoute("app_login");
        }
       
    }

    #[Route('/api/utilisateurs', name: 'api_utilisateurs_liste', methods: ['GET'])]
    public function listeUtilisateurs(UserRepository $userRepository): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $currentUser = $this->getUser();

        // Récupérer la liste de tous les utilisateurs en excluant l'utilisateur connecté
        $utilisateurs = $userRepository->findAllExceptCurrentUser($currentUser);

        $data = [];
        foreach ($utilisateurs as $utilisateur) {
            $data[] = [
                'id' => $utilisateur->getId(),
                'username' => $utilisateur->getEmail(),
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        // données sous forme de réponse JSON
        return $this->json($data);
    }

    #[Route('/api/utilisateurs/delete/{id}', name: 'api_utilisateurs_delete', methods: ['DELETE'])]
    public function supprimerUtilisateur(User $utilisateur): JsonResponse
{
    $entityManager = $this->entityManager;
    $entityManager->remove($utilisateur);
    $entityManager->flush();

    return new JsonResponse(null, Response::HTTP_NO_CONTENT);
}
}
