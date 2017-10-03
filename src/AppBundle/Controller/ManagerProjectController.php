<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Projects;
use AppBundle\Form\ProjectsType;
use AppBundle\Form\ProjectsTypeEdit;
use AppBundle\Service\ManagerProjectService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\html;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ManagerProjectController extends Controller
{ 
    public function __Construct(ManagerProjectService $projectService){
        $this->projectService=$projectService;
    }
    /**
     * @Route("/{_locale}",name="index",defaults={"_locale":"en"},requirements={"_locale":"en|vn"})
     */
    public function showAction(Request $request){
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    	return $this->render('/manager/index.html.twig');
    }
    /**
     * @Route("/projectsAjax")
     */
    public function ajaxListProject(Request $request){
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $entities=$this->getDoctrine()->getRepository('AppBundle:Projects')->findAllOrderByCreatedAt();
     
        $projects=$this->projectService->getProjetsAjax($entities);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($projects));

       return $response;
    }
    

    /**
     * @Route("/projectAjax/{id}")
     */
    public function ajaxProject(Request $request,$id){
      // check user admin
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

      // check ajax reaquest
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        // get value datatbase
        $entity=$this->getDoctrine()->getRepository(Projects::class)->find($id);
        if(!$entity)
            return $this->redirectToRoute('errors');   
        $project=$this->projectService->getProjetAjax($entity);

        // make a reaponse
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($project));

        // return a response
       return $response;
    }
    


    /**
     * @Route("/{_locale}/create",name="create",defaults={"_locale":"en"},requirements={"_locale":"en|vn"})
     * @Method({"GET","POST"})
     */

    public function createAction(Request $request){
    // check admin
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

		// create new entity
    $project=new Projects;

    // updated timestamps, set value project entity using projectManagerService
		$project=$this->projectService->updatedTimestamps($project);
		$project= $this->projectService->setValueProject($project);

    // render form
		$form = $this->createForm(ProjectsType::class, $project);

    // hanlde request form
    $form->handleRequest($request);

    // validation and insert database
		if($form->isSubmitted() && $form->isValid()){
			$this->insertDB($project);
      $this->addFlash("success",$this->get('translator')->trans("session.success.message"));
		  
			return $this->redirectToRoute('index');
		}
 
		return $this->render('manager/create.html.twig', ['form' => $form->createView()]);   	
    }

    /**
     * @Route("edit/{_locale}/{id}",name="edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction($id,Request $request){
      // check admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // get value datatbase
        $project=$this->getDoctrine()->getRepository(Projects::class)->find($id);
        if(!$project)
            return $this->redirectToRoute('errors');
        
      // updated timestamps using projectManagerService
        $this->projectService->updatedTimestamps($project);

        // create form 
        $form = $this->createForm(ProjectsTypeEdit::class, $project, [ 'method' => 'PUT']);

        // handle request
        $form->handleRequest($request);

        // check value and update database
        if($form->isSubmitted() && $form->isValid()){
            $this->insertDB($project);
            $this->addFlash("success",$this->get('translator')->trans("session.success.message"));
            return $this->redirectToRoute('index');
        }

        return $this->render('manager/editProject.html.twig', ['form' => $form->createView()]);   
    }

    public function insertDB($project){
        $em=$this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
    }

    /**
     * @Route("delete",name="delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request){
      // check admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
      // check token  
        $csrf = $request->get('_csrf_token');
        if ($this->isCsrfTokenValid('csrf_provider', $csrf)) {
          
          // get id_select[] from request form
          $id_select= $request->get('id_select');

          //delete 
          foreach($id_select as $id){
            $project=$this->getDoctrine()->getRepository(Projects::class)->find($id);
            if(!$project)
                return $this->redirectToRoute('errors');
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
          }
            return $this->redirectToRoute("index");
          return 1;
        } else return $this->redirectToRoute("index");
    }
}	