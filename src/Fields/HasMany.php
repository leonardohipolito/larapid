<?php

namespace Internexus\Larapid\Fields;

use Illuminate\Database\Eloquent\Model;
use Internexus\Larapid\Http\Resources\LarapidResource;

class HasMany extends Relational
{
    /**
     * Field component name.
     *
     * @var string
     */
    public static $component = 'has-many';

    /**
     * Show field only on index.
     *
     * @var boolean
     */
    protected $showOnIndex = false;

    /**
     * Show field only on creating.
     *
     * @var boolean
     */
    protected $showOnCreating = false;

    /**
     * Show field only on updating.
     *
     * @var boolean
     */
    protected $showOnUpdating = false;

    /**
     * Show field only on detail.
     *
     * @var boolean
     */
    protected $showOnDetail = false;

    /**
     * Get relation method name.
     *
     * @return string
     */
    public function getMethod()
    {
        if ($this->method) {
            return $this->method;
        }

        return lcfirst($this->label);
    }

    /**
     * Get relation data.
     *
     * @param Model $model
     * @return \Illuminate\Support\Collection
     */
    public function getRelation(Model $model)
    {
        $data = collect([]);
        $method = $this->getMethod();

        if (isset($model->{$method})) {
            $data = $model->{$method}()->paginate();
        }

        return $data;
    }

    /**
     * Get field options.
     *
     * @param Model $model
     * @return mixed
     */
    public function getResource(Model $model)
    {
        $data = $this->getRelation($model);
        $request = clone request();
        $entity = $this->resolveRelationEntity();

        $parentEntity = $request->entity;
        $request->entity = $entity;

        $resource = LarapidResource::collection($data);

        return [
            'data' => [
                'data' => $resource->toArray($request)
            ],
            'title' => $entity::$title,
            'columns' => $entity->getIndexColumns(),
            'fields' => $entity->getCreatingFieldsProps(),
            'routes' => [
                'create' => $entity->route(null, 'create') . '?' . http_build_query([
                    'relatedId' => $model->id,
                    'relatedEntity' => $request->entity->slug(),
                    'relatedColumn' => $this->column,
                ]),
                'attach' => $parentEntity->route($model->id, 'attach') . '?' . http_build_query([
                    'relatedEntity' => $request->entity->slug(),
                    'relatedColumn' => $this->column,
                ]),
            ],
        ];
    }
}
