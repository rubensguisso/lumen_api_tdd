<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUser()
    {
        $dados = [
            'name' => 'fulano gomes',
            'email' => 'fulano@gmail.com',
            'password' => '123',
        ];
        
        $this->post('api/user', $dados);
        $this->assertResponseOK();

        $resposta = (array) json_decode($this->response->content());

        $this->assertArrayHaskey('name',$resposta);
    }


}
