<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [ 
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Je crée une instance de la classe Category
        $category = new Category(); 

        //Je crée un formulaire, à partir de la classe CategoryType
        //J'injecte l'objet $category dans le formulaire
        $form = $this->createForm(CategoryType::class, $category);
 
        //Elle permet d'aller chercher les données dans la request
        $form->handleRequest($request);

        //Je vérifie si le formulaire a bien été rempli et convenablement 
        if ($form->isSubmitted() && $form->isValid()) {

            //il persiste l'entity à envoyer en BDD
            //Il verifie si toutes les données obligatoires  pour enregistrer une categorie ont bien été remplie 
            $entityManager->persist($category);

            //La fonction flush permet de vraiment envoyer les infos en BDD
            $entityManager->flush();

            //Je fais une redirection vers la page de listing des catégories
            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        //si je suis dans un cas ou le formulaire n'a pas encore été soumis
        //Il gere l'affichage du formulaire
        //Pour cela j'envoie entre autre dans la vue, une variable qui represente le formulaire que j'ai construit
        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_category_show', methods: ['GET'])]
    public function show(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

         if(!$category){

            $this->addflash("danger","La catégorie est introuvable");
            return $this->redirectToRoute("admin_category_index");
         }

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
