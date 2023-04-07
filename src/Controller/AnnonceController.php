<?php

namespace App\Controller;

use App\Entity\Acquisition;
use App\Entity\Annonce;
use App\Entity\Commentary;
use App\Form\AcquisitionType;
use App\Form\AnnonceType;
use App\Form\CommentaryType;
use App\Repository\AcquisitionRepository;
use App\Repository\AddressRepository;
use App\Repository\AnnonceRepository;
use App\Repository\CommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/admin', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/admin/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setUser($this->getUser());
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET', 'POST'])]
    public function show(Annonce $annonce, Request $request, CommentaryRepository $commentaryRepository): Response
    {
        $commentary = new Commentary();
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentary->setAnnonce($annonce);
            $commentary->setUser($this->getUser());
            $commentaryRepository->save($commentary, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/transaction/{id}', name: 'app_annonce_transaction', methods: ['POST', 'GET'])]
    public function transaction(Request $request, Annonce $annonce, AcquisitionRepository $acquisitionRepository, AddressRepository $addressRepository): Response
    {
        $acquisition = new Acquisition ();
        $form = $this->createForm(AcquisitionType::class, $acquisition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $acquisition->setUser($this->getUser());
            $acquisition->setAnnonce($annonce);
            $acquisitionRepository->save($acquisition, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/transaction.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'address' => $addressRepository->findBy(['user' => $this->getUser()])
        ]);
    }
}
