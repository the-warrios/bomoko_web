<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class TestVideoController extends AbstractController
{

//    private $entityManager;
//
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }

    /**
     * @Route("/api/video/upload", name="video_upload", methods={"POST"})
     */
    #[Route('/api/video/upload', name: 'video_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        // Vérifiez si un fichier a bien été uploadé
        $file = $request->files->get('video');
        if (!$file) {
            throw new BadRequestHttpException('No video file uploaded');
        }

        // Vérifiez que le fichier est bien une vidéo
        $mimeType = $file->getMimeType();
        if (!str_contains($mimeType, 'video')) {
            throw new BadRequestHttpException('Invalid file type');
        }

        // Générer un nom unique pour la vidéo et déplacer le fichier
        $filename = uniqid().'.'.$file->guessExtension();
        $file->move($this->getParameter('videos_directory'), $filename);

        // Sauvegarder l'information dans la base de données
//        $video = new Video();
//        $video->setFilename($filename);
//        $this->entityManager->persist($video);
//        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'filename' => $filename], Response::HTTP_CREATED);
    }


    #[Route('/test/video', name: 'app_test_video')]
    public function index(): Response
    {
        return $this->render('test_video.html.twig');
    }
}
