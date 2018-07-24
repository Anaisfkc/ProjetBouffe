<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Form\PropositionType;
use App\Repository\PropositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proposition")
 */
class PropositionController extends Controller
{
    /**
     * @Route("/", name="proposition_index", methods="GET")
     */
    public function index(PropositionRepository $propositionRepository): Response
    {
        return $this->render('proposition/index.html.twig', ['propositions' => $propositionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="proposition_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $proposition = new Proposition();
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proposition);
            $em->flush();

            return $this->redirectToRoute('proposition_index');
        }

        return $this->render('proposition/new.html.twig', [
            'proposition' => $proposition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposition_show", methods="GET")
     */
    public function show(Proposition $proposition): Response
    {
        return $this->render('proposition/show.html.twig', ['proposition' => $proposition]);
    }

    /**
     * @Route("/{id}/edit", name="proposition_edit", methods="GET|POST")
     */
    public function edit(Request $request, Proposition $proposition): Response
    {
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proposition_edit', ['id' => $proposition->getId()]);
        }

        return $this->render('proposition/edit.html.twig', [
            'proposition' => $proposition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposition_delete", methods="DELETE")
     */
    public function delete(Request $request, Proposition $proposition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proposition->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proposition);
            $em->flush();
        }

        return $this->redirectToRoute('proposition_index');
    }

    /**
     * @Route("/filpropositions", name="filpropositions")
     */
    public function filpropositions(Request $request): Response
    {
        $propositions = $this->getDoctrine()
        ->getRepository(Proposition::class)
        ->find();
        return $this->render('proposition/filpropositions.html.twig', ['propositions' => $propositions]);
    }
}
