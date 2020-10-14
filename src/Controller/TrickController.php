<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Images;
use App\Entity\Videos;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Security\Voter\TrickVoter;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Paginator;

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
                    $img->setName();
                    $trick->addImage($img);
                    if($img ===null){
                        $img->move($this->getParameter('img_profile_directory'),$fichier);
                    }
                }

                $video = $form->get('videos')->getData();
                
                    $url = new Videos();
                    $url->setUrl($video);
                    $trick->addVideo($url);
                    //dd($trick);

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
            * @Route("/{id}/show/{page<\d+>?1}", name="trick_show", methods={"GET","POST"})
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
                        'trick' => $trick,
                        'paginator' => $paginator,
                        'form' => $form->createView(),
                        ]);
                    
                }

                /**
                * @Route("/{id}/newcomment/{page<\d+>?1}", name="comment_new", methods={"GET","POST"})
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
                        ]);
                    }
                    $paginator
                            ->setEntityClass(Comment::class)
                            ->setOrder(['creation_date' => 'DESC'])
                            ->setPage($page)
                            ->setAttribut(['trick' => $trick]);
                           
                            
                    
                    return $this->render('trick/show.html.twig', [
                        'trick' => $trick,
                        'paginator' => $paginator,
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
                            $img->setName();
                            $trick->addImage($img);
                        }
                        $video = $form->get('videos')->getData();
                
                    $url = new Videos();
                    $url->setUrl($video||null);
                    $trick->addVideo($url);
                    //dd($trick);

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
                public function deleteImage(Images $image, Request $request)
                {
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

                /**
                 * @Route("/delete/video/{id}", name="trick_delete_video", methods={"DELETE"})
                 */
                public function deleteVideo(Videos $video, Request $request)
                {
                    $this->denyAccessUnlessGranted('ROLE_USER');
                    if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($video);
                        $entityManager->flush();
                    }
                    return $this->redirectToRoute('trick_index');
                }

                /**
                 * @Route("/delete/videos/{id}", name="trick_delete_videos", methods={"DELETE"})
                 */
               /* public function deleteVideos(Videos $video, Request $request)
                {
                    $data = json_decode($request->getContent(), true);
                    if($this->isCsrfTokenValid('delete'.$video->getId(), $data['_token'])){
                        
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($video);
                        $entityManager->flush();
                    
                    return new JsonResponse(['success' => 1]);
                }else{
                    return new JsonResponse(['error' => 'Token Invalide'], 400);
                }
            }*/
            }