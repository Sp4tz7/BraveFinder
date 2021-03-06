<?php

namespace App\Controller;

use App\Entity\Practice;
use App\Form\PracticeType;
use App\Repository\PracticeRepository;
use App\Repository\EvaluationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/practice")
 * @IsGranted("ROLE_ADMIN")
 */
class PracticeController extends AbstractController
{
    /**
     * @Route("/", name="practice.index", methods={"GET"})
     */
    public function index(PracticeRepository $practiceRepository): Response
    {
        return $this->render('practice/index.html.twig', [
            'practices' => $practiceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="practice.new", methods={"GET","POST"})
     */
    public function new(Request $request, EvaluationRepository $EvaluationRepository): Response
    {
        $practice = new Practice();
        $form = $this->createForm(PracticeType::class, $practice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($practice);
            $entityManager->flush();

            $EvaluationRepository->addNewEvaluations();

            return $this->redirectToRoute('practice.index');
        }

        return $this->render('practice/new.html.twig', [
            'practice' => $practice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="practice.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Practice $practice): Response
    {
        $form = $this->createForm(PracticeType::class, $practice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $practice->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('practice.index');
        }
        return $this->render('practice/edit.html.twig', [
            'practice' => $practice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="practice.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Practice $practice): Response
    {
        if ($this->isCsrfTokenValid('practice.delete' . $practice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($practice);
            $entityManager->flush();
        }
        return $this->redirectToRoute('practice.index');
    }
}
