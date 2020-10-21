<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request){
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|confirmed|max:255',
        ]);

        $user = new User($request->all());
        $user->api_token = Str::random(60);
        $user->save();
        return $user;
    }

    public function login(Request $request){
        $dados = $request->only('email','password');
        $user = User::where('email', $dados['email'])
                    ->where('password', $dados['password'])
                    ->first();
        $user->api_token = Str::random(60);
        $user->update();            
        return  ['api_token' => $user->api_token ];
    }

    public function update(Request $request, $id){
        $dadosValidator = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
        ];

        if(isset($request->all()['password'])){
            $dadosValidador['password'] = 'required|confirmed|max:255';
        }

        $this->validate($request, $dadosValidator);

        $user = User::find($id);
        $user->name = $request->input('name');    
        $user->email = $request->input('email');  
        if(isset($request->all()['password'])){
            $user->password = $request->input('password');
        }  
        $user->update();
        return $user;
    }

    public function view($id){
        return User::find($id);
    }

    public function delete($id){
        if(User::destroy($id)){
            return response('Removido com sucesso',200);          
        }
        return response('Erro ao remover', 400);
    }

    public function list(){
        return User::all();
    }


}
