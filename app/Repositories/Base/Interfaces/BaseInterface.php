<?php

namespace App\Repositories\Base\Interfaces;

interface BaseInterface
{
    public function all();
    public function create( array $data, $with = [] );
    public function update( array $data, $id, $with = [] );
    public function insert( array $data );
    public function where( $param1, $condition, $param2 );
    public function getById( $id, $with = [] );
    public function list( $skip, $take, $request, $with = [], $filter = [], $where=[], callable $callback = null );
    public function searchFilters( $query, $search, $filters = ['byId', 'byName'] );
    public function returnEmptyList();
    public function delete( $id );
}
