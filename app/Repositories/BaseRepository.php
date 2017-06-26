<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


abstract class BaseRepository
{

    protected $resource;

    public function getResource()
    {
        return $this->resource;
    }

    function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    public function getRules()
    {
        return $this->resource->rules;
    }

    public function getFields()
    {
        return $this->resource->getFields();
    }

    public function find($id)
    {
        try {

            $this->resource = $this->resource->query()->findOrFail($id);

            return $this->resource;

        } catch (\Exception $e) {
            $message = "Error occurred while finding resource identifier "
                . $id . " of type " . get_class($this->resource);

            Log::error($e->getMessage());
            throw new \Exception($message);
        }
    }

    public function findAll()
    {
        try {

            $this->resource = $this->resource->paginate(config('app.pagination'));

            return $this->resource;

        } catch (\Exception $e) {
            $message = "Error occurred while finding resources of type "
                . get_class($this->resource);

            Log::error($e->getMessage());
            throw new \Exception($message);
        }
    }

    public function store($data)
    {
        try {

            $this->resource = new $this->resource;

            foreach ($this->getFields() as $field) {
                $this->resource[$field] = $data[$field];
            }

            $this->resource->save();

            return $this->resource;

        } catch (\Exception $e) {

            $message = "Error occurred while creating resource of type "
                . get_class($this->resource);

            Log::error($e->getMessage());
            throw new \Exception($message);
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $this->resource = $this->resource->query()->find($id);

            if (!$this->resource ) {
                $message = "Resource not found while updating with identifier "
                    . $id . " of type " . get_class($this->resource);

                Log::error($message);
                throw new \Exception($message);
            }

            foreach ($this->getFields() as $field) {

                if (!empty($request[$field])) {
                    $this->resource[$field] = $request[$field];
                }

            }
            $this->resource->save();

            return $this->resource;

        } catch (\Exception $e) {
            $message = "Error occurred while updating resource identifier "
                . $id . " of type " . get_class($this->resource);

            Log::error($e->getMessage());
            throw new \Exception($message);
        }
    }


    public function delete($id)
    {
        try {

            $this->resource = $this->resource->find($id);

            if ($this->resource) {

                $this->resource->delete();

                return $this->resource;

            } else {
                $message = "Resource not found while deleting with identifier "
                    . $id . " of type " . get_class($this->resource);

                Log::error($message);
                throw new \Exception($message);
            }
        } catch (\Exception $e) {
            $message = "Error occurred while deleting resource identifier "
                . $id . " of type " . get_class($this->resource);

            Log::error($e->getMessage());
            throw new \Exception($message);
        }
    }
}