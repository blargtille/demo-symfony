<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //aller chercher les séries en BDD
        //$series = $serieRepository->findAll();
        //aller chercher les 30 + populaires
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC'], 30);
        $series = $serieRepository->findBestSeries();

        dump($series);

        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }
    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, SerieRepository $serieRepository): Response{
       //aller chercher la série en BDD
            $serie = $serieRepository->find($id);

        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response{

        dump($request);
        return $this->render('/series/create.html.twig');
    }

    #[Route('/demo', name: 'demo')]
    public function demo(EntityManagerInterface $entityManager){
        $serie = new Serie();
        $serie->setName('pif');
        $serie->setBackdrop('test');
        $serie->setPoster('poster');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime('- 1 year'));
        $serie->setLastAirDate(new \DateTime('- 6 months'));
        $serie->setGenre('Fantasy');
        $serie->setOverview('blabla');
        $serie->setVote(1.5);
        $serie->setPopularity(5.60);
        $serie->setStatus('cancelled');
        $serie->setTmdbId(1234);

        dump($serie);

        //enregistre la série en BDD
        $entityManager->persist($serie);
        //fait un commit
        $entityManager->flush();

        dump($serie);
        //suppression de la série
        //$entityManager->remove($serie);
        //$entityManager->flush();

        //modifier la série
        $serie->setGenre('comic');
        $entityManager->flush();

        return $this->render('serie/create.html.twig');
    }
}
