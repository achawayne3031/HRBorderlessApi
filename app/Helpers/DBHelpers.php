<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class DBHelpers
{


    ////// get all query data
    public static function query_paginate(
        $dataModel,
        $with_data,
        $location,
        $is_remote,
        $search,
        $limit = 20
    ) {

        $jobs = $dataModel::query()->with($with_data);

        if ($location) {
            $jobs->where('location', 'LIKE', '%' . $location . '%');
        }

        if ($is_remote) {
            $jobs->where('is_remote', $is_remote);
        }

        if ($search) {
            $jobs->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $jobs->latest();
        $jobs = $jobs->paginate($limit);
        return $jobs;
    }


    public static function soft_delete($dataModel, $id)
    {
        $project = $dataModel::find($id);
        return $project->delete();
    }



    public static function count($dataModel, $data = null)
    {

        if ($data == null) {
            return $dataModel::count();
        }
        return $dataModel
            ::query()
            ->where($data)
            ->count();
    }




    //////  query where filter first data
    public static function query_filter_first($dataModel, $filter)
    {

        return $dataModel
            ::where($filter)
            ->get()
            ->first();
    }


    public static function exists($dataModel, $data)
    {

        return $dataModel
            ::query()
            ->where($data)
            ->exists();
    }


    public static function with_query($dataModel, $with_clause = [])
    {
        return $dataModel
            ::query()
            ->with($with_clause)
            ->orderBy('id', 'DESC')
            ->get();
    }



    public static function where_query($dataModel, $where_clause = [])
    {
        return $dataModel
            ::query()
            ->where($where_clause)
            ->get();
    }

    public static function with_where_query_filter_first(
        $dataModel,
        $with_clause = [],
        $where = null
    ) {
        if ($where == null) {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->get()
                ->first();
        } else {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->where($where)
                ->get()
                ->first();
        }
    }



    /////// where with query ////
    public static function with_where_query_filter(
        $dataModel,
        $with_clause = [],
        $where = []
    ) {
        if ($where == null) {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->where($where)
                ->orderBy('id', 'DESC')
                ->get();
        }
    }



    //////  query where filter data
    public static function query_filter($dataModel, $filter)
    {

        return $dataModel
            ::where($filter)
            ->orderBy('id', 'DESC')
            ->get();
    }





    ////// get all query data
    public static function all_data($dataModel)
    {
        return $dataModel::all();
    }







    ////// Update flexible /////
    public static function update_query_v3($dataModel, $data, $filter = null)
    {
        DB::beginTransaction();
        $status = null;

        if ($filter != null) {
            $status = $dataModel::where($filter)->update($data);
        } else {
            $status = $dataModel::query()->update($data);
        }
        DB::commit(); // execute the operations above and commit transaction
        return $status;
    }



    //////// Insert query data /////////
    public static function create_query($dataModel, $data)
    {
        return $dataModel::create($data);
    }
}
