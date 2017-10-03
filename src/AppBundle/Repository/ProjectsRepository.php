<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Projects;

/**
 * ProjectsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectsRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllOrderByCreatedAt(){
    	return $this->findBy([],['createdAt' => 'DESC']);
	}

	
   
}
