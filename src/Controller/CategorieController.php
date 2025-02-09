<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Historique;


#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            // After persisting the category, create a Historique entity
             $historique = new Historique();
             $historique->setEtat('CATEGORY ADDED');
             $historique->setDate(new \DateTime());

            // Persist the Historique entity
             $entityManager->persist($historique);
             $entityManager->flush();
             $this->addFlash('success', 'Your Category has been added successfully.');


            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
        

    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated category
            $entityManager->flush();
    
            // Create a Historique entity for the update action
            $historique = new Historique();
            $historique->setEtat('CATEGORY UPDATED');
            $historique->setDate(new \DateTime());
    
            // Persist the Historique entity
            $entityManager->persist($historique);
            $entityManager->flush();
            $this->addFlash('success', 'Your Category has been updated successfully.');

    
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            // Create a Historique entity for the deletion action
            $historique = new Historique();
            $historique->setEtat('CATEGORY DELETED');
            $historique->setDate(new \DateTime());
    
            // Persist the Historique entity
            $entityManager->persist($historique);
    
            // Remove the category
            $entityManager->remove($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Your category have been deleted successfully.');
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
