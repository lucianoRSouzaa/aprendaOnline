<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;

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

    public function delete($videoName)
    {
        $deleted = Storage::disk('google')->delete($videoName);

        return $deleted;
    }
}
