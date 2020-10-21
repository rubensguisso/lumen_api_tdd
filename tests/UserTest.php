<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
   
    use DatabaseMigrations;

    public function testCreateUser()
    {
        $dados = [
            'name' => 'fulano gomes',
            'email' => 'fulano3@gmail.com',
            'password' => '123',
            'password_confirmation' => '123',
        ];        
        
        $this->post('api/user', $dados);
        //echo $this->response->content();
        $this->assertResponseOK();

        $result = (array) json_decode($this->response->content());

        $this->assertArrayHaskey('name',$result);
        $this->assertArrayHaskey('email',$result);
        $this->assertArrayHaskey('id',$result);
    } 

    public function testLogin(){
        $dados = [
            'name' => 'fulano gomes',
            'email' => 'fulano3@gmail.com',
            'password' => '123',
            'password_confirmation' => '123'
        ];
        
        $this->post('api/user', $dados);
        //echo $this->response->content();
        $this->assertResponseOK();

        $this->post('api/login', $dados);
        $this->assertResponseOK();

        $result = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('api_token',$result);

    }

    public function testViewUser(){
        $user = \App\User::first();
        
        $this->get('api/user/'.$user->id);
        echo $this->response->content();
        $this->assertResponseOk();

        $result = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('name',$result);
        $this->assertArrayHasKey('email',$result);
        $this->assertArrayHasKey('id',$result);
    }

    public function testUpdateUserNoPassword(){

        $user = \App\User::first();
        $dados = [
            'name' => 'fulano gomes2',
            'email' => 'fulano3@gmail.com',
        ];
        
        $this->put('api/user/'.$user->id,$dados);
        //echo $this->response->content();
        $this->assertResponseOK();

        $result = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('name',$result);
        $this->assertArrayHasKey('email',$result);
        $this->assertArrayHasKey('id',$result);
        
        $this->seeInDatabase('users', [
            'name' => $dados['name'],
            'email' => $dados['email']
        ]); 
    }

    public function testUpdateUserWithPassword(){

        $user = \App\User::first();
        $dados = [
            'name' => 'fulano gomes2',
            'email' => 'fulano3@gmail.com',
            'password' => '123',
            'password_confirmation' => '1232'
        ];
        
        $this->put('api/user/'.$user->id,$dados);
        //echo $this->response->content();
        $this->assertResponseOK();

        $result = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('name',$result);
        $this->assertArrayHasKey('email',$result);
        $this->assertArrayHasKey('id',$result);
        
        $this->seeInDatabase('users', [
            'name' => $dados['name'],
            'email' => $dados['email']
        ]); 
    }

    public function testUserAll(){
        $this->get('api/users');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' =>  [
                'id',
                'name',
                'email'
            ]
        ]);
    }

    public function testDeleteUser(){
        $user = \App\User::first();
        $this->delete('api/user/'.$user->id);
        $this->assertResponseOk();
        $this->assertEquals("Removido com sucesso", $this->response->content());
    }
}
