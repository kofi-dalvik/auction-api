<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * Prevents the id from mass assignment
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Optional list of all relations the model has for eager loading
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Get the id of a resource by name
     *
     * @param string $resource
     * @param string $column
     * @return int
     */
    public static function getResourceId(string $resource, string $column = 'name')
    {
        $resource = self::where($column, $resource)->first();

        if ($resource) {
            return $resource->id;
        }

        return null;
    }

    /**
     * Get the list of relations this model has.
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Get the primary key of a model
     *
     * @return string
     */
    public static function getPrimaryKey()
    {
        return (new static)->getKeyName();
    }

    /**
     * Get the table name of a model
     *
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /**
     * Specifies if instance can be deleted
     *
     * @return bool
     */
    public function canDelete()
    {
        return true;
    }
}
