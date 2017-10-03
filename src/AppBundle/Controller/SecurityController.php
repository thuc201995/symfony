<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
	 * @Route("/login",name="login")
	 */
    
    public function login(Request $request, AuthenticationUtils $authUtils){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage
            return $this->redirectToRoute('index');
        }
         // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

    	return $this->render('manager/login.html.twig',['last_username' => $lastUsername,
        'error'=> $error]);
    }

    
    /**
     * @Route("/errors",name="errors")
     */
    public function showErrors(){
        return $this->render('/manager/errors.html.twig');
    }

    /**
     * @Route("/deny")
     */
    public function showDeny(){
        return $this->render('/manager/accessDeny.html.twig');
    }
}	
