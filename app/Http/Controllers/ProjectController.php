<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Utils;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = Project::with('user')->get();
        return Utils::responseJson(
            Response::HTTP_OK,
            $projects->count() === 0 ? 'No se encontraron proyectos' : 'Datos encontados satisfactoriamente',
            $projects,
            Response::HTTP_OK
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return JsonResponse
     */
    public function store(ProjectRequest $request)
    {
        try {

            $user = Auth::user();
            $request->merge(['user_id' => $user->id]);
            $project = Project::create($request->all());
            return Utils::responseJson(
                Response::HTTP_NO_CONTENT,
                'Proyecto creado satisfactoriamente',
                $project,
                Response::HTTP_CREATED
            );
        }catch (Exception $e){
            return Utils::responseJson(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Ha ocurrido un error al procesar la solicitud:',
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $project = Project::with('user')->find($id);
        return Utils::responseJson(
            Response::HTTP_OK,
            'Proyecto encontrado satisfactoriamente',
            $project,
            Response::HTTP_OK
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = Project::find($id);
            $project->update($request->all());
            return Utils::responseJson(
                Response::HTTP_OK,
                'Proyecto actualizado satisfactoriamente',
                $project,
                Response::HTTP_OK
            );
        }catch (Exception $e){
            return Utils::responseJson(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Ha ocurrido un error al procesar la solicitud:',
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $project = Project::find($id);
            $project->delete();
            DB::commit();
            return Utils::responseJson(
                Response::HTTP_NO_CONTENT,
                'Proyecto eliminado satisfactoriamente',
                [],
                Response::HTTP_OK
            );
        }catch (Exception $e){
            DB::rollBack();
            return Utils::responseJson(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Ha ocurrido un error al procesar la solicitud:',
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
