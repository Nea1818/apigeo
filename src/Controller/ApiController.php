<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/listeRegions", name="listeRegions")
     */
    public function listRegion(SerializerInterface $serializer)
    {
        $mesRegions = file_get_contents('https://geo.api.gouv.fr/regions');
        //$mesRegionsTab = $serializer->decode($mesRegions, 'json');
        //$mesRegionsObjet= $serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]');
        $mesRegions = $serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');
        // dump($mesRegionsObjet);
        // die();
        return $this->render('api/index.html.twig', [
          'mesRegions' => $mesRegions
        ]);
    }

    /**
    * @Route("/listeDeptParRegion", name="listeDeptParRegion")
    */
    public function listDeptParRegion(Request $request, SerializerInterface $serializer)
    {
        
        // Je récupère la région sélectionnée dans le formulaire
        $codeRegion = $request->query->get('region');
        
        
        // Je récupère les régions et je les serialize en objet
        $mesRegions = file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions = $serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');
        
        // Je récupère mes départements
        if ($codeRegion == null || $codeRegion == "Toutes") {
            $mesDepts = file_get_contents('https://geo.api.gouv.fr/departements');
        } else {
            $mesDepts = file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }

        // Je décode les départements en tab
        $mesDepts = $serializer->decode($mesDepts, 'json');

        return $this->render('api/listeDeptParRegion.html.twig', [
          'mesRegions' => $mesRegions,
          'mesDepts' => $mesDepts
        ]);
    }
}
