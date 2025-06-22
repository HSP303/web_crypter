<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Tests;
use DateTime;
use Illuminate\Http\Request;
use  GuzzleHttp\Client ; 

class VirustotalController extends Controller
{
    private Client $client;

    public function index(){
        $user = auth()->user(); 

        $tests = Tests::whereHas('file', function($query) use ($user) {
            $query->where('user', $user->name);
        })->get();

        return view('virustotal.lista', compact('tests'));
    }

    public function createTest(){
        $user = auth()->user(); 

        $files = Files::where('user', $user->name)->get();

        //return dd($files);
        return view('virustotal.create', compact('files'));
    }

    public function updateTest(Request $request, $id){
        $user = auth()->user(); 

        $test = Tests::where('id', $id)->whereHas('file', function($query) use ($user) {
            $query->where('user', $user->name);
        })->first();

        $hoje = new DateTime();
        $hoje = $hoje->format('d/m/y');
        $description = $request->input('description');
        if(!$description){
            $description = "Done on " . $hoje; 
        }

        $test->description = $description;
        $test->save();

        //return dd($files);
        return redirect()->route('virustotal.get')->with('success', 'Teste editado com sucesso!');
    }

    public function editTest($id){
        $user = auth()->user(); 

        $test = Tests::where('id', $id)->whereHas('file', function($query) use ($user) {
            $query->where('user', $user->name);
        })->first();

        $file = Files::where('user', $user->name)->where('id', $test['file_id'])->first();

        //return $id;
        return view('virustotal.edit', compact('file', 'test'));
    }


    public function requestApi(Request $request){
        $this->client = new Client();
        $test = new Tests();

        $request->validate([
            'file' => 'required'
        ]);

        $user = auth()->user(); 
        $file = Files::where('id', $request->input('file'))->where('user', $user->name)->first(); 

        $filepath = '/opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/'.$file->path;

        $responseUpload = $this->client->request('POST', 'https://www.virustotal.com/api/v3/files', [
        'multipart' => [
            [
            'name' => 'file',
            'contents' => fopen($filepath, 'r'), // Abre o arquivo no modo leitura
            'filename' => basename($filepath) // Pega apenas o nome do arquivo
            ]
        ],
        'headers' => [
            'accept' => 'application/json',
            'x-apikey' => '56e33426f3a9e463071a579c228e425d4fdb0ecaed87e64e9ecea3305584d69c' // Substitua pela sua chave real
        ]
        ]);

        $responseUpload = json_decode($responseUpload->getBody(), true);
        $id = $responseUpload['data']['id'];
        
        
        $responseStatus = $this->client->request('GET', 'https://www.virustotal.com/api/v3/analyses/'.$id, [
        'headers' => [
            'accept' => 'application/json',
            'x-apikey' => '56e33426f3a9e463071a579c228e425d4fdb0ecaed87e64e9ecea3305584d69c',
        ],
        ]);

        $responseStatus = json_decode($responseStatus->getBody(), true);
        $score = $responseStatus['data']['attributes']['stats']['malicious'] + $responseStatus['data']['attributes']['stats']['suspicious'];
        $qtdtests = $responseStatus['data']['attributes']['stats']['malicious'] + $responseStatus['data']['attributes']['stats']['suspicious'] + $responseStatus['data']['attributes']['stats']['undetected'];

        $description = $request->input('description');
        

        $hoje = new DateTime();
        $hoje = $hoje->format('d/m/y');

        if(!$description){
            $description = "Done on " . $hoje; 
        }
        Tests::create([
                'file_id' => $file->id,
                'score' => $score,
                'qtdtests' => $qtdtests,
                'description' => $description
            ]);

        return redirect()->route('virustotal.get')->with('success', 'Testado com sucesso!');
    }

    public function deleteTest($id){
        $user = auth()->user(); 

        $test = Tests::where('id', $id)->whereHas('file', function($query) use ($user) {
            $query->where('user', $user->name);
        })->first();

        $test->delete();

        return redirect()->back()->with('success', 'Teste excluído com sucesso!');
    }

}
