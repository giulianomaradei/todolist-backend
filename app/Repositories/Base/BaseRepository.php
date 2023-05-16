<?php

namespace App\Repositories\Base;

use App\Exceptions\CustomException;
use App\Repositories\Base\Interfaces\BaseInterface;

use function PHPUnit\Framework\isEmpty;

abstract class BaseRepository implements BaseInterface
{
    protected $model;

    public function newInstance(){
        return $this->model->newInstance();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create( array $data, $with = [] )
    {
        return $this->model->create($data)->fresh($with);
    }

    public function update( array $data, $id, $with = [] )
    {
        $model = $this->model->find($id);
        if( ! $model )
            throw new CustomException(__('module.not-found'), 404);
        $model->fill( $data );
        $model->save();

        return $model->fresh($with);
    }

    public function insert( array $data )
    {
        return $this->model->insert($data);
    }

    public function where( $param1, $condition, $param2 )
    {
        return $this->model->where($param1, $condition, $param2);
    }

    public function getById( $id, $with = [] )
    {
        $result = $this->model->with($with)->find($id);
        if( ! $result )
            throw new CustomException(__('module.not-found'), 404);
        return $result;
    }

    public function list( $skip, $take, $request, $with = [], $filter = [], $where=[], callable $callback = null )
    {
        $query = $this->model->with( $with );

        foreach($where as $w)
            $query = $query->where( $w['column'], $w['operator'], $w['value'] );

        $query = $this->searchFilters( $query, $request->search, $filter );

        if( ! $query ) {
            if( $request->simple ) return [];
            return $this->returnEmptyList();
        }

        if( $request->order_field )
            $query->orderby($request->order_field, $request->order_type);

        if( $request->simple )
            return $query->get();

        $total  = $query->count();
        $result = $query->take( $take )->skip( $skip )->get();

        return [
            'total_items' => $total,
            'pages' => ( $total <= $take ) ? 1 : ceil($total / $take),
            'list' => $callback ? $callback($result) : $result
        ];

    }

    public function searchFilters( $query, $search, $filters = ['byId', 'byName'] )
    {
        if ( empty( $search ) ) return $query;

        foreach ($filters as $filter) {
            if ( with(clone($query))->$filter( $search )->count() ) {
                return $query->$filter( $search );
            }
        }

        return false;
    }

    public function returnEmptyList()
    {
        return [
            'list'        => [],
            'pages'       => 0,
            'total_items' => 0
        ];
    }

    public function delete( $id )
    {
        return $this->model->find($id)->delete();
    }
}
