<?php
namespace App\Controller;

use App\Entity\Merci;
use App\Entity\Employee;  // Ajouter cette ligne
use App\Form\MerciType;
use App\Repository\MerciRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MerciController extends AbstractController
{
    /**
     * @Route("/", name="merci_index")
     */
     public function index(MerciRepository $merciRepository, Security $security, Request $request): Response
         {
             // Récupère l'utilisateur authentifié
             $user = $security->getUser();

             // Vérifie que l'utilisateur est authentifié
             if (!$user) {
                 return $this->redirectToRoute('app_login');
             }

             // Recherche de critères (recherche par message et tri par date)
             $searchTerm = $request->query->get('search', '');
             $sortByDate = $request->query->get('sortByDate', 'desc');  // 'desc' ou 'asc'

             // Récupère les mercis envoyés et reçus
             $mercisEnvoyes = $merciRepository->findBy([
                 'fromEmployee' => $user
             ], ['date' => $sortByDate]);

             $mercisRecus = $merciRepository->findBy([
                 'toEmployee' => $user
             ], ['date' => $sortByDate]);

             // Recherche dans les messages (si un terme est fourni)
             if ($searchTerm) {
                 $mercisEnvoyes = array_filter($mercisEnvoyes, function ($merci) use ($searchTerm) {
                     return stripos($merci->getMessage(), $searchTerm) !== false;
                 });
                 $mercisRecus = array_filter($mercisRecus, function ($merci) use ($searchTerm) {
                     return stripos($merci->getMessage(), $searchTerm) !== false;
                 });
             }

             // Envoie les données à la vue
             return $this->render('merci/index.html.twig', [
                 'mercisEnvoyes' => $mercisEnvoyes,
                 'mercisRecus' => $mercisRecus,
                 'searchTerm' => $searchTerm,
                 'sortByDate' => $sortByDate,
             ]);
         }
//     public function index(MerciRepository $merciRepository, Security $security): Response
//         {
//             // Récupère l'utilisateur authentifié
//             $user = $security->getUser();
//
//             // Vérifie que l'utilisateur est authentifié
//             if (!$user) {
//                 // Redirige vers la page de login si l'utilisateur n'est pas connecté
//                 return $this->redirectToRoute('app_login');
//             }
//
//             // Récupère les mercis pour cet utilisateur
//             $mercis = $merciRepository->findByUser($user);
//
// //             // Débogage : afficher les mercis dans la barre d'outils Symfony
// //             dump($mercis); // Cela devrait être visible dans le Profiler de Symfony (en bas de la page)
//
//             return $this->render('merci/index.html.twig', [
//                 'mercis' => $mercis,
//             ]);
//         }

    /**
     * @Route("/merci/new", name="merci_new")
     */
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $merci = new Merci();
        $user = $security->getUser();  // Récupère l'utilisateur connecté

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère tous les employés sauf l'utilisateur connecté
        $employees = $em->getRepository(Employee::class)->findAll();

        // Filtrer pour exclure l'employé connecté
        $employees = array_filter($employees, function($employee) use ($user) {
            return $employee !== $user;  // Exclure l'employé connecté
        });

        $form = $this->createForm(MerciType::class, $merci, [
            'employees' => $employees,  // Passer la liste filtrée d'employés au formulaire
            'user' => $user,  // Passer l'utilisateur connecté au formulaire
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $merci->setFromEmployee($user);  // Associe le "merci" à l'utilisateur authentifié
            $merci->setDate(new \DateTime()); // Ajoute la date actuelle
            $em->persist($merci);
            $em->flush();

            return $this->redirectToRoute('merci_index');
        }

        return $this->render('merci/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//     /**
//      * @Route("/merci/edit/{id}", name="merci_edit")
//      * @ParamConverter("merci", class="App\Entity\Merci")
//      */
    /**
     * @Route("/merci/edit/{id}", name="merci_edit")
     * @ParamConverter("merci", class="App\Entity\Merci")
     */
    public function edit(Request $request, Merci $merci, EntityManagerInterface $em): Response {
        // Vérification de l'accès : un utilisateur ne peut éditer que ses propres mercis
        if ($merci->getFromEmployee() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous ne pouvez modifier que vos propres mercis.");
        }

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère tous les employés sauf l'utilisateur connecté
        $employees = $em->getRepository(Employee::class)->findAll();
        $employees = array_filter($employees, function($employee) use ($user) {
            return $employee !== $user;  // Exclure l'employé connecté
        });

        // Création du formulaire en passant la liste des employés et l'utilisateur connecté
        $form = $this->createForm(MerciType::class, $merci, [
            'employees' => $employees,  // Passer la liste filtrée d'employés au formulaire
            'user' => $user  // Passer l'utilisateur connecté au formulaire
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('merci_index');
        }

        return $this->render('merci/edit.html.twig', [
            'form' => $form->createView(),
            'merci' => $merci,
        ]);
    }


    /**
     * @Route("/merci/delete/{id}", name="merci_delete", methods={"POST"})
     * @ParamConverter("merci", class="App\Entity\Merci")
     */
    public function delete(Request $request, Merci $merci, EntityManagerInterface $em): Response {
        // Vérification que l'utilisateur a créé ce message
        if ($merci->getFromEmployee() === $this->getUser()) {
            $em->remove($merci);
            $em->flush();
        }

        return $this->redirectToRoute('merci_index');
    }


//     /**
//      * @Route("/merci/edit/{id}", name="merci_edit")
//      */
//     public function edit(Request $request, $id, MerciRepository $merciRepository, EntityManagerInterface $em): Response {
//         // Récupérer l'entité Merci en fonction de l'ID
//         $merci = $merciRepository->find($id);
//
//         // Vérifier si l'entité existe
//         if (!$merci) {
//             throw $this->createNotFoundException('Le message Merci n\'existe pas.');
//         }
//
//         // Vérification de l'accès : un utilisateur ne peut éditer que ses propres mercis
//         if ($merci->getFromEmployee() !== $this->getUser()) {
//             throw $this->createAccessDeniedException("Vous ne pouvez modifier que vos propres mercis.");
//         }
//
//         // Créer le formulaire
//         $form = $this->createForm(MerciType::class, $merci);
//         $form->handleRequest($request);
//
//         // Si le formulaire est soumis et valide, on met à jour l'entité
//         if ($form->isSubmitted() && $form->isValid()) {
//             $em->flush();
//             return $this->redirectToRoute('merci_index');
//         }
//
//         return $this->render('merci/edit.html.twig', [
//             'form' => $form->createView(),
//             'merci' => $merci,
//         ]);
//     }
//
//
//     /**
//      * @Route("/merci/delete/{id}", name="merci_delete", methods={"POST"})
//      */
//     public function delete(Request $request, Merci $merci, EntityManagerInterface $em): Response {
//         if ($merci->getFromEmployee() === $this->getUser()) {
//             $em->remove($merci);
//             $em->flush();
//         }
//
//         return $this->redirectToRoute('merci_index');
//     }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void {
        // Symfony gère automatiquement la déconnexion
    }
}
