<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use App\Models\LessonFile;


class FileController extends Controller
{
    public function upload($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();

        Storage::disk('google')->put($filename, file_get_contents($file));

        $googleDriveId = Storage::disk("google")->getAdapter()->getMetadata($filename)->extraMetadata()['id'];

        Storage::disk('google')->setVisibility($filename, 'public');

        // Criando um novo registro na tabela "videos"
        $video = new Video([
            'name' => $filename,
            'id_google_drive' => $googleDriveId,
        ]);

        $video->save();

        // Retorna o ID do vídeo criado no banco de dados
        return $video->id;
    }

    public function file($file, $lesson_id)
    {        
        $filename = time() . '_' . $file->getClientOriginalName();

        Storage::disk('google')->put($filename, file_get_contents($file));
        Storage::disk('google')->setVisibility($filename, 'public');

        $fileContent = new LessonFile();

        $fileContent->name = $filename;
        $fileContent->lesson_id = $lesson_id;

        $fileContent->save();

        return true;
    }

    public function download($filename)
    {
        $file = Storage::disk('google')->get($filename); 

        // definindo os cabeçalhos da resposta HTTP
        $headers = [
            'Content-Type' => Storage::disk('google')->mimeType($filename),
            // indica que o arquivo deve ser baixado, e o nome do arquivo é definido como o guardado na variável
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        // retornando código de sucesso
        return response($file, 200, $headers);
    }

    public function delete($videoName)
    {
        $deleted = Storage::disk('google')->delete($videoName);

        return $deleted;
    }
}
