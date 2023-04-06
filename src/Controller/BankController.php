<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Form\BankType;
use App\Repository\BankRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bank')]
class BankController extends AbstractController
{
    #[Route('/', name: 'app_bank_index', methods: ['GET'])]
    public function index(BankRepository $bankRepository): Response
    {
        return $this->render('bank/index.html.twig', [
            'banks' => $bankRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bank_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BankRepository $bankRepository): Response
    {
        $bank = new Bank();
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankRepository->save($bank, true);

            return $this->redirectToRoute('app_bank_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank/new.html.twig', [
            'bank' => $bank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_show', methods: ['GET'])]
    public function show(Bank $bank): Response
    {
        return $this->render('bank/show.html.twig', [
            'bank' => $bank,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bank_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bank $bank, BankRepository $bankRepository): Response
    {
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankRepository->save($bank, true);

            return $this->redirectToRoute('app_bank_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank/edit.html.twig', [
            'bank' => $bank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_delete', methods: ['POST'])]
    public function delete(Request $request, Bank $bank, BankRepository $bankRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bank->getId(), $request->request->get('_token'))) {
            $bankRepository->remove($bank, true);
        }

        return $this->redirectToRoute('app_bank_index', [], Response::HTTP_SEE_OTHER);
    }
}
