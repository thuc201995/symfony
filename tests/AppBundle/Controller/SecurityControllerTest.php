<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
   	protected $client=null;

    public function setup(){
        $this->client = static::createClient();
    }

    /** @test */
    public function redirect_login_page_if_not_login(){
   		$crawler = $this->client->request('GET', '/');
   		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

   	}

    /** @test */
    public function login_failed(){
      $this->login("not_auth","password");
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

    }

   	public function testLogin(){
      $this->login("admin","test");
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/'));
      $crawler = $this->client->request('GET', '/en/create');

      $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    protected function login($userName,$password){
      $crawler = $this->client->request('GET', '/login');
      $buttonCrawlerNode = $crawler->selectButton('Submit');
      $form = $buttonCrawlerNode->form();
      $data = array('_username' => $userName,'_password' => $password);
      $this->client->submit($form,$data);
    }

    public function testLogout(){
      $this->login("admin","test");
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/'));

      $crawler=$this->client->request('GET','/logout');
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));
   
    }

    /** @test */
    public function if_not_admin_can_not_access_create_edit_delete_page(){
      $this->login('user','test');
      $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/'));
      
      // check user is login
      $this->client->request('GET', '/en');
      $this->assertEquals(200,$this->client->getResponse()->getStatusCode());
      //check denied 
      $crawler = $this->client->request('GET', '/en/create');
      $this->assertSame('403 Access Denied', $crawler->filter('h1')->text());

      $crawler = $this->client->request('GET', '/edit/en/1');
      $this->assertSame('403 Access Denied', $crawler->filter('h1')->text());

      $crawler = $this->client->request('DELETE', '/delete/en/1');
      $this->assertSame('403 Access Denied', $crawler->filter('h1')->text());
    }
}	