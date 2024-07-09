<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Models\Media;
use App\Models\Propertie;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'properties_id' => 'required|integer',
            'title' => 'required|string',
            'url' => 'required|string',
        ]);

        $getPropertie = Propertie::where('id', $request->properties_id)->exists();

        if (!$getPropertie) {
            return Responses::NOTFOUND('Propriedade não encontrada!');
        }

        try {
            $createMedia = Media::create($validated);

            return Responses::CREATED('Mídia criada com sucesso!', $createMedia);
        }
        catch(\Exception $e) {
            return Responses::BADREQUEST('Ocorreu um erro ao tetar criar uma mídia!');
        }
    }

    public function destroy($id)
    {
        $getPropertie = Propertie::where('id', $id)->first();

        if (!$getPropertie) {
            return Responses::NOTFOUND('Propriedade não encontrada');
        }

        $deleteMedia = Media::where('id', $id)->delete();

        return Responses::OK('Imagem excluída com sucesso!');
    }
}
