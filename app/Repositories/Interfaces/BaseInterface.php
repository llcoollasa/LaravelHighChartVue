<?php

namespace App\Repositories\Interfaces;


use Illuminate\Http\Request;

interface BaseInterface
{

    /**
     * find resource by id
     *
     * @param  int $id resource id
     * @return object     resource of type
     */
    public function find($id);

    /**
     * find all resources
     *
     * @return array resources of type
     */
    public function findAll();

    /**
     * store a resource
     *
     * @param  array $data resource data
     * @return object       resource of type
     */
    public function store($data);

    /**
     * update a resource
     *
     * @param  Request $request
     * @param  integer $id resource id
     * @return object  resource of type
     */
    public function update(Request $request, $id);

    /**
     * delete a resource
     *
     * @param  integer $id resource id
     * @return object      resource of type
     */
    public function delete($id);

}