<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManagerProjectsControllerTest extends WebTestCase
{
	protected $client=null;

    public function setup(){
        $this->client = static::createClient();
    }

    public function testCreateProject(){
    	$this->login('admin','test');

    	$crawler=$this->client->request('GET','/');

		$this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    	$crawler=$this->client->click($crawler->selectLink('Create Project')->link());

    	$buttonCrawlerNode=$crawler->selectButton("Create");
    	$form=$buttonCrawlerNode->form();

    	$data=[];
    	$data=$this->createData($data,$action="create");

    	$this->client->submit($form,$data);

    	$crawler = $this->client->followRedirect();
    	$this->assertGreaterThan(0, $crawler->filter('html:contains("Test")')->count());

    }

     public function testEditProject(){
    	$this->login('admin','test');

    	$crawler=$this->client->request('GET','/');

		$this->assertEquals(200,$this->client->getResponse()->getStatusCode());
    	$crawler=$this->client->click($crawler->selectLink('Edit')->link());

    	$buttonCrawlerNode=$crawler->selectButton("Save");
    	$form=$buttonCrawlerNode->form();

    	$data=[];
    	$data=$this->createData($data,$action="edit");

    	$this->client->submit($form,$data);
    	
    	$crawler = $this->client->followRedirect();
    	$this->assertGreaterThan(0, $crawler->filter('html:contains("edit")')->count());

    }

    public function testDeleteProject(){
    	$this->login('admin','test');
		$crawler=$this->client->request('GET','/');

      	$this->client->submit($crawler->selectButton('Delete')->form());
        $crawler = $this->client->followRedirect();
    }

    protected function login($userName,$password){
      $crawler = $this->client->request('GET', '/login');
      $buttonCrawlerNode = $crawler->selectButton('Submit');
      $form = $buttonCrawlerNode->form();
      $data = array('_username' => $userName,'_password' => $password);
      $this->client->submit($form,$data);
    }

    protected function createData($data,$action){
    	return $data=[
    		'appbundle_projects[name]'=>'symfonyTest'.$action,
    		'appbundle_projects[start][date][year]'=>'2017',
    		'appbundle_projects[start][date][month]'=>'1',
    		'appbundle_projects[start][date][day]'=>'10',
    		'appbundle_projects[start][time][hour]'=>'9',
    		'appbundle_projects[start][time][minute]'=>'0',

    		'appbundle_projects[end][date][year]'=>'2017',
    		'appbundle_projects[end][date][month]'=>'1',
    		'appbundle_projects[end][date][day]'=>'10',
    		'appbundle_projects[end][time][hour]'=>'9',
    		'appbundle_projects[end][time][minute]'=>'0',

    		'appbundle_projects[performer]'=>"Test",
    		'appbundle_projects[content]'=>"yêu cầu nhập tối thiểu 20 ký tự"
    	];
    }
}	