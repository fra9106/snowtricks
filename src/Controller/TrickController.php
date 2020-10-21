<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Images;
use App\Entity\Videos;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Security\Voter\TrickVoter;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            'tricks' => $trickRepository->findBy([],['creation_date' => 'DESC'])
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
            $trick->getSlug('name');
            $trick->setCreationDate(new \Datetime());
            
            $form = $this->createForm(TrickType::class, $trick);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('message', 'Trick added!');
            
                $images = $form->get('images')->getData();
                
                foreach($images as $image) {
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    
                    $img = new Images();
                    $img->setFile($fichier);
                    $trick->addImage($img);
                    if($img ===null){
                        $img->move($this->getParameter('img_profile_directory'),$fichier);
                    }
                }//dd($trick);

                    foreach($trick->getVideos() as $video) {
                        $video->setTrick($trick);
                    
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
            * @Route("/{slug}/show/{page<\d+>?1}", name="trick_show", methods={"GET","POST"})
            */
            public function show(CommentRepository $repo,Request $request, Trick $trick, Paginator $paginator, $page): Response
            {
                $form = $this->createForm(CommentType::class);
                $form->handleRequest($request);
                $paginator
                            ->setEntityClass(Comment::class)
                            ->setOrder(['creation_date' => 'DESC'])
                            ->setPage($page)
                            ->setAttribut(['trick' => $trick]);
                           
                            
                    
                    return $this->render('trick/show.html.twig', [
                        'slug' => $trick->getSlug(),
                        'trick' => $trick,
                        'paginator' => $paginator,
                        'form' => $form->createView(),
                        ]);
                    
                }

                /**
                * @Route("/{slug}/newcomment/{page<\d+>?1}", name="comment_new", methods={"GET","POST"})
                */
                public function newComment(Request $request, Trick $trick, EntityManagerInterface $manager,Paginator $paginator, $page) : response
                {
                    $this->denyAccessUnlessGranted('ROLE_USER');
                    $comment = new Comment();
                    $comment->setUser($this->getUser());
                    $form = $this->createForm(CommentType::class, $comment);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->addFlash('message', 'Comment added!');
                        $comment->setCreationDate(new \DateTime())
                                ->setTrick($trick);
                        $manager->persist($comment);
                        $manager->flush();
                        
                        return $this->redirectToRoute('trick_show', [
                            'id' => $trick->getId(),
                            'slug' => $trick->getSlug(),
                        ]);
                    }
                    $paginator
                            ->setEntityClass(Comment::class)
                            ->setOrder(['creation_date' => 'DESC'])
                            ->setPage($page)
                            ->setAttribut(['trick' => $trick]);
                           
                            
                    
                    return $this->render('trick/show.html.twig', [
                        'trick' => $trick,
                        'slug' => $trick->getSlug(),
                        'paginator' => $paginator,
                        'form' => $form->createView(),
                        ]);
                }
                
                /**
                * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
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
                            $img->setFile($fichier);
                            $trick->addImage($img);
                        }
                        $this->getDoctrine()->getManager()->flush();

                        $videos = $form->get('videos')->getData();
                        foreach ($videos as $video) {
                            $video->getTrick()->removeElement($trick);
                            $this->manager->persist($video);
                        }
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
                
               
            }
            