<?php

namespace App\Repository;

use App\Entity\Medecin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Medecin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medecin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medecin[]    findAll()
 * @method Medecin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedecinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medecin::class);
    }

    /**
     * @return Medecin[]
     * Fonction permettant de rechercher dans la base un médecin selon ce qui à été recherché
     * Ex : entrée "Henri" Résultat : Tous les médecins présentant henri dans leur nom, prénom, adresse, ville et spécialité
     */
    public function findBySearch($searchField){
        $search = "%".$searchField."%";
        return $this->createQueryBuilder('m')
            ->innerJoin('m.laSpecialite','sp')
            ->select('m')
            ->andWhere('sp.libelle like :search')
            ->orWhere('m.prenom like :search')
            ->orWhere('m.nom like :search')
            ->orWhere('m.adresse like :search')
            ->orWhere('m.ville like :search')
            ->setParameter('search',$search)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Medecin[] Returns an array of Medecin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Medecin
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
