<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wish", name= "wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findPublishedWishesWithCategories();
        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes,
        ]);
    }

    /**
     * @Route("/wish/detail/{id}", name= "wish_details", requirements={"id"="\d+"})
     */
    public function details(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        return $this->render("wish/details.html.twig", [
            "wish" => $wish,
        ]);
    }

    /**
     * @Route("/wish/create", name= "wish_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted()) {

            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('succes', 'Wish added ! Good job :)');

            return $this->redirectToRoute('wish_details', ['id'=>$wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm->createView()

        ]);
    }


}
