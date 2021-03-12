<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entity;
use App\Http\Resources\EntityResource;
use App\Http\Resources\EntityResourceCollection;
use App\Http\Requests\EntityStoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EntityController extends Controller
{

    /**
     * Set Request variables based on validation rule keys to Entity and return it.
     * This would transform if the eloyouent model would be different from request
     # through the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entity
     *
     * @return \App\Models\Entity
     */
    private function transformRequestToEntity (EntityStoreRequest $request, Entity &$entity)
    {
        foreach($request->rules() as $key => $value) {
            if (isset($request->$key)) {
                $entity->$key = $request->$key;
            }
        }
        return $entity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $esr = new EntityStoreRequest();

        $sortables = $esr->requiredRules();
        if (!isset($request->orderBy)) {
            $request->orderBy = [];
        }
        if (!isset($request->first) || !in_array($request->first, [5, 10, 20])) {
            $request->first = 10;
        }
        if (!isset($request->orderBy['column']) || !in_array($request->orderBy['column'], $sortables)) {
            $request->orderBy = array_merge($request->orderBy,['column' => $sortables[0]]);
        }
        if (!isset($request->orderBy['order']) ||
            !in_array($request->orderBy['order'], ['ASC', 'DESC'])) {
                $request->orderBy = array_merge($request->orderBy, ['order' => 'ASC']);
        }
        $orderer = $request->orderBy['order'] == 'DESC' ? 'orderByDesc' : 'orderBy';
        return new EntityResourceCollection(Entity::$orderer($request->orderBy['column'])->paginate($request->first)->
        withPath('/entity')->
        withQueryString()->
        appends(['orderBy'   => [
                    'column' => $request->orderBy['column'],
                    'order'  => $request->orderBy['order']
                    ]
               ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntityStoreRequest $request)
    {
        return $this->storeToModel($request, 1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function storeToModel(EntityStoreRequest &$request, bool $insert = true, $id =0)
    {
        if ($request->validated()) {
            $entity = $insert ? new Entity : Entity::findOrFail($id);
            $this->transformRequestToEntity($request, $entity);
            $entity->save();
            $messageType = $insert ? 'stored' : 'updated';
            return (new EntityResource($entity))->additional(['message' => __("entity.{$messageType}_success", $insert ? [] : ['id' => $id])]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return new EntityResource(Entity::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntityStoreRequest $request, int $id)
    {
        return $this->storeToModel($request, 0, $id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Entity::destroy($id);
        return response()->json(['message' => __('entity.destroyed_success', ['id' => $id])]);
    }
}
