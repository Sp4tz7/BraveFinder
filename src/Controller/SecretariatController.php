<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\CandidateSearch;
use App\Form\CandidateMesureType;
use App\Form\CandidateOfficeType;
use App\Form\CandidateSearchType;
use App\Form\CandidateReceptionType;
use App\Repository\CandidateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/secretariat")
 */

class SecretariatController extends AbstractController
{
    /**
     * @Route("/reception", name="secretariat.reception")
     * @Route("/")
     * @IsGranted("ROLE_ACCUEIL,ROLE_ADMIN")
     * 
     */
    public function reception(Request $request, CandidateRepository $candidateRepository)
    {
        $search = new CandidateSearch;
        $form = $this->createForm(CandidateSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('secretariat/index.html.twig', [
            'form' => $form->createView(),
            'candidates' => $candidateRepository->findAllQuery($search),
            'job' => 'reception',
        ]);
    }

    /**
     * @Route("/reception/{id}/edit", name="secretariat.reception.edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ACCUEIL,ROLE_ADMIN")
     * 
     */
    public function editreception(Request $request, Candidate $candidate)
    {
        $form = $this->createForm(CandidateReceptionType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secretariat.reception');
        }
        return $this->render('secretariat/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
            'job' => 'reception',
        ]);
    }

    /**
     * @Route("/mesure", name="secretariat.mesure")
     * @IsGranted("ROLE_MESURE,ROLE_ADMIN")
     */
    public function mesure(Request $request, CandidateRepository $candidateRepository)
    {
        $search = new CandidateSearch;
        $form = $this->createForm(CandidateSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('secretariat/index.html.twig', [
            'form' => $form->createView(),
            'candidates' => $candidateRepository->findAllOnSiteQuery($search),
            'job' => 'mesure',
        ]);
    }

    /**
     * @Route("/mesure/{id}/edit", name="secretariat.mesure.edit", methods={"GET","POST"})
     * @IsGranted("ROLE_MESURE,ROLE_ADMIN")
     */
    public function editMesure(Request $request, Candidate $candidate)
    {
        $form = $this->createForm(CandidateMesureType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secretariat.mesure');
        }
        return $this->render('secretariat/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
            'job' => 'mesure',
        ]);
    }

    /**
     * @Route("/administratif", name="secretariat.administratif")
     * @IsGranted("ROLE_SECRETARIAT,ROLE_ADMIN")
     * 
     */
    public function administratif(Request $request, CandidateRepository $candidateRepository)
    {
        $search = new CandidateSearch;
        $form = $this->createForm(CandidateSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('secretariat/index.html.twig', [
            'form' => $form->createView(),
            'candidates' => $candidateRepository->findAllOnSiteQuery($search),
            'job' => 'office',
        ]);
    }

    /**
     * @Route("administratif/{id}/edit", name="secretariat.administratif.edit", methods={"GET","POST"})
     * @IsGranted("ROLE_SECRETARIAT,ROLE_ADMIN")
     * 
     */
    public function editAdministratif(Request $request, Candidate $candidate)
    {
        $form = $this->createForm(CandidateOfficeType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secretariat.administratif');
        }
        return $this->render('secretariat/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form->createView(),
            'job' => 'office',
        ]);
    }
}
