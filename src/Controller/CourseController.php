<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChauffeurRepository;

use App\Form\CourseType;

final class CourseController extends AbstractController
{

    private $entityManager;

    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    #[Route('/courses', name: 'course_index')]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        return $this->render('course/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/course/new', name: 'course_new')]
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement dans la base de données
            $course->setStatut('en attente');
            $this->entityManager->persist($course);
            $this->entityManager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/course/assign', name: 'course_assign')]
    public function assign(Request $request, EntityManagerInterface $entityManager, CourseRepository $courseRepository, ChauffeurRepository $chauffeurRepository): Response
    {
        // Récupère les courses en attente
        $courses = $courseRepository->findBy(['statut' => 'en attente']);
        
        // Récupère les chauffeurs disponibles
        $chauffeurs = $chauffeurRepository->findBy(['disponible' => true]);

        if ($request->isMethod('POST')) {
            $courseId = $request->request->get('course_id');
            $chauffeurId = $request->request->get('chauffeur_id');

            $course = $courseRepository->find($courseId);
            $chauffeur = $chauffeurRepository->find($chauffeurId);

            if ($course && $chauffeur) {
                $course->setChauffeur($chauffeur);
                $course->setStatut('en cours');
                $chauffeur->setDisponible(false);

                $entityManager->persist($course);
                $entityManager->persist($chauffeur);
                $entityManager->flush();

                $this->addFlash('success', 'Chauffeur assigné à la course avec succès !');
                return $this->redirectToRoute('course_index');
            }

            $this->addFlash('error', 'Erreur lors de l’assignation.');
        }

        return $this->render('course/assign.html.twig', [
            'courses' => $courses,
            'chauffeurs' => $chauffeurs,
        ]);
    }

    #[Route('/course/{id}/complete', name: 'course_complete')]
    public function complete(Course $course, EntityManagerInterface $entityManager): Response
    {
        $course->setStatut('terminé');
        $chauffeur = $course->getChauffeur();
        if ($chauffeur) {
            $chauffeur->setDisponible(true);
        }

        $entityManager->flush();

        return $this->redirectToRoute('course_index');
    }



}
