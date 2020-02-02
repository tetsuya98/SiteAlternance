<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Competence;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/competences")
 */
class CompetenceControllerController extends Controller
{

    /**
     * @Route("/new", name="competences_new")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function addCompetenceAction(Request $request)
    {
        // Vérification si champ vide
        $title = $request->get('title','');
        if (empty($title)){
            return new JsonResponse(json_encode(['error' => 'empty']));
        }

        // Vérification si compétence déjà existente
        $em = $this->getDoctrine()->getManager();
        $research = $em->getRepository(Competence::class)
            ->createQueryBuilder('c')
            ->where('upper(c.competences) = upper(:title)')
            ->setParameter('title',$title)
            ->getQuery()
            ->execute();
        if (!empty($research)) {
            return new JsonResponse(json_encode(['error' => 'unique']));
        }

        // Création de la nouvelle compétence
        $new = new Competence();
        $new->setCompetences($title);
        $em->persist($new);
        $em->flush();

        // Retour de la nouvelle compétence en json
        return new JsonResponse(json_encode([
            'id' => $new->getId(),
            'title' => $new->getCompetences()
        ]));
    }

}
