<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Images;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Security\Voter\TrickVoter;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

/**
* @Route("/trick")
*/
class TrickController extends AbstractController
{
    /**
    * @Route("/", name="trick_index", methods={"GET"})
    */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            ]);
        }
        
        /**
        * @Route("/new", name="trick_new", methods={"GET","POST"})
        */
        public function new(Request $request): Response
        {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $trick = new Trick();
            $trick->setUser($this->getUser());
            $trick->setCreationDate(new \Datetime());
            
            $form = $this->createForm(TrickType::class, $trick);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $images = $form->get('images')->getData();
                foreach($images as $image) {
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    
                    $img = new Images();
                    $img->setName($fichier);
                    $trick->addImage($img);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($trick);
                $entityManager->flush();
                
                return $this->redirectToRoute('trick_index');
            }
            
            return $this->render('trick/new.html.twig', [
                'trick' => $trick,
                'form' => $form->createView(),
                ]);
            }
            
            /**
            * @Route("/{id}/show", name="trick_show", methods={"GET","POST"})
            */
            public function show(Trick $trick): Response
            {
                return $this->render('trick/show.html.twig', [
                    'trick' => $trick,
               
                ]);
                
            }

                /**
                * @Route("/{id}/newcomment", name="comment_new", methods={"GET","POST"})
                */
                public function newComment(Request $request, Trick $trick, EntityManagerInterface $manager) : response
                {
                    $this->denyAccessUnlessGranted('ROLE_USER');
                    $comment = new Comment();
                    $comment->setUser($this->getUser());
                    $form = $this->createForm(CommentType::class, $comment);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $comment->setCreationDate(new \DateTime())
                                ->setTrick($trick);
                        $manager->persist($comment);
                        $manager->flush();
                        return $this->redirectToRoute('trick_show', [
                            'id' => $trick->getId(),
                        ]);
                    }
                    return $this->render('trick/show.html.twig', [
                        'trick' => $trick,
                        'form' => $form->createView(),
                        ]);
                }
                
                /**
                * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
                */
                public function edit(Request $request, Trick $trick): Response
                {
                    $this->denyAccessUnlessGranted(TrickVoter::EDIT, $trick);
                    $trick->setUpdateDate(new \Datetime());
                    
                    $form = $this->createForm(TrickType::class, $trick);
                    $form->handleRequest($request);
                    
                    if ($form->isSubmitted() && $form->isValid()) {
                        
                        $images = $form->get('images')->getData();
                        foreach($images as $image) {
                            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                            
                            $image->move(
                                $this->getParameter('images_directory'),
                                $fichier
                            );
                            
                            $img = new Images();
                            $img->setName($fichier);
                            $trick->addImage($img);
                        }
                        
                        $this->getDoctrine()->getManager()->flush();
                        
                        return $this->redirectToRoute('trick_index'
                    );
                }
                
                return $this->render('trick/edit.html.twig', [
                    'trick' => $trick,
                    'form' => $form->createView(),
                    ]);
                }
                
                /**
                * @Route("/{id}/delete", name="trick_delete", methods={"DELETE"})
                */
                public function delete(Request $request, Trick $trick): Response
                {
                    $this->denyAccessUnlessGranted('ROLE_USER');
                    if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($trick);
                        $entityManager->flush();
                    }
                    
                    return $this->redirectToRoute('trick_index');
                }
                
                /**
                * @Route("/delete/image/{id}", name="trick_delete_image", methods={"DELETE"})
                */
                public function deleteImage(Images $image, Request $request){
                    $data = json_decode($request->getContent(), true);
                    // We check if the token is valid
                    if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
                        // We get the name of the image
                        $name = $image->getName();
                        // We delete the file
                        unlink($this->getParameter('images_directory').'/'.$name);
                        // We delete the entry from the database
                        $em = $this->getDoctrine()->getManager();
                        $em->remove($image);
                        $em->flush();
                        // We answer in json
                        return new JsonResponse(['success' => 1]);
                    }else{
                        return new JsonResponse(['error' => 'Token Invalide'], 400);
                    }
                }
            }
            