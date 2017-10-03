<?php
namespace AppBundle\Service;



class ManagerProjectService
{

    public function updatedTimestamps($project){
        $project->setUpdatedAt(new \DateTime('now'));
        if($project->getCreatedAt()==null)
            $project->setCreatedAt(new \DateTime('now'));
        return $project;
    }

    public function pagination($projects,$paginator,$request){
    	$pagination = $paginator->paginate(
        $projects, 
        $request->query->getInt('page', 1),
        6/*limit per page*/);
    	return $pagination;
    }

    public function setValueProject($project){
    	$project->setStart(new \DateTime('now'));
        $project->setStatus(0);
        $project->setEnd(new \DateTime('now'));
		return $project;
    }

    public function getProjetsAjax($entities){
           $projects=[];
         foreach ($entities as $entity) {
              array_push($projects,[
                "id"=>$entity->getId(),
                "name"=>$entity->getName(),
                "status"=>$entity->getStatus(),
                "created_at"=>$entity->getCreatedAt()->format('Y-m-d H:i:s'),
                "updated_at"=>$entity->getUpdatedAt()->format('Y-m-d H:i:s')
             ]);
        }
        return $projects;
    }

    public function getProjetAjax($entity){
     $project=[];
            array_push($project,[
                "content"=>$entity->getContent(),
                "performer"=>$entity->getPerformer(),
                "start"=>$entity->getStart()->format('Y-m-d H:i:s'),
                "end"=>$entity->getEnd()->format('Y-m-d H:i:s')
                
            ]);

        
        return $project;
    }

}	