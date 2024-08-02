<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Models\Propertie;
use Illuminate\Http\Request;

class PropertieController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'price' => 'required',
            'location' => 'required|string',
            'category' => 'required|string',
        ]);

        try {
            $createdPropertie = Propertie::create($validated);

            return Responses::CREATED('Imóvel criado com sucesso!', $createdPropertie);
        }
        catch (\Exception $e) {
            return Responses::BADREQUEST('Ocorreu um erro ao criar uma propriedade!');
        }
    }

    public function index()
    {
        $getProperties = Propertie::orderBy('is_active', 'DESC')->orderBy('id', 'DESC')->with('medias', function ($query) {
            $query->orderBy('id', 'DESC')->select(['id', 'title', 'url', 'properties_id']);
        })->get(['id', 'title', 'description', 'location', 'price', 'is_active']);

        return Responses::OK('', $getProperties);
    }

    public function public(Request $request)
    {
        $categoryFilter = $request->query('category', 'todos');

        if ($categoryFilter == 'todos') {
            $getProperties = Propertie::where('is_active', 1)->orderBy('id', 'DESC')->with('medias', function ($query) {
                $query->orderBy('id', 'DESC')->select(['id', 'title', 'url', 'properties_id']);
            })->get(['id', 'title', 'description', 'location', 'price', 'is_active', 'category']);
        } else {
            $getProperties = Propertie::where('is_active', 1)->where('category', $categoryFilter)->orderBy('id', 'DESC')->with('medias', function ($query) {
                $query->orderBy('id', 'DESC')->select(['id', 'title', 'url', 'properties_id']);
            })->get(['id', 'title', 'description', 'location', 'price', 'is_active']);
        }


        return Responses::OK('', $getProperties);
    }

    public function home_categories()
    {
        $activeProperties = Propertie::where('is_active', 1)->with('medias', function ($query) {
            $query->orderBy('id', 'DESC')->select(['id', 'title', 'url', 'properties_id']);
        })->orderBy('id', 'DESC')->get();

        $groupedProperties = $activeProperties->groupBy('category')->map(function ($group) {
            return $group->take(6);
        });

        return Responses::OK('', $groupedProperties);
    }

    public function show($id)
    {
        $getPropertie = Propertie::select('id', 'title', 'description', 'location', 'category', 'price')
            ->where('id', $id)->where('is_active', 1)->with('medias', function ($query) {
                $query->orderBy('id', 'DESC')->select(['id', 'title', 'url', 'properties_id']);
            })->first();

        if (!$getPropertie) {
            return Responses::NOTFOUND('Propriedade não encontrada!');
        }

        return Responses::OK('', $getPropertie);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'string|min:1',
            'description' => 'string|min:1',
            'location' => 'string|min:1',
            'price' => 'min:1',
            'is_active' => 'integer',
        ]);

        $getPropertie = Propertie::where('id', $id)->first();

        if (!$getPropertie) {
            return Responses::NOTFOUND('Propriedade não encontrada');
        }

        $getPropertie->update($validated);
        $getPropertie->save();

        return Responses::OK('Propriedade atualizada com sucesso!');
    }
}
