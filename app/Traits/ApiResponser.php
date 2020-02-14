<?php
 namespace App\Traits;

//use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    private function successResponse($data,$code)
    {
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$code)
    {
        return response()->json(['error' => $message,'code'=>$code],$code);
    }

    protected function showAll(Collection $collection,$code = 200)
    {
        if($collection->isEmpty()){
            return $this->successResponse(['data' => $collection],$code);
        }
        $transformer  = $collection->first()->transformer;

        $collection = $this->filterData($collection,$transformer);
        $collection = $this->sortData($collection,$transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection,$transformer);
        return $this->successResponse($collection,$code);
    }

    protected function showOne(Model $model,$code = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model,$transformer);
        return $this->successResponse($model,$code);
    }

    protected function showMessage($message,$code = 200)
    {
        return $this->successResponse(['data' => $message],$code);
    }

    /**
     * @param Collection $collection
     * @param $transformer
     * @return Collection|mixed
     */
    protected function sortData(Collection $collection,$transformer)
    {
        if(request()->has('sort_by')){
            $attribute = $transformer::originalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    /**
     * @param Collection $collection
     * @param $transformer
     * @return Collection|mixed
     */
    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value){
            $attribute = $transformer::originalAttributes($query);
            if(isset($attribute,$value)){
                $collection = $collection->where($attribute,$value);
            }
        }
        return $collection;
    }

    /**
     * @param Collection $collection
     */
    protected function paginate(Collection $collection)
    {
        $rules = [
          'per_page' => 'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(),$rules);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }
        $result = $collection->slice(($page - 1) * $perPage,$perPage)->values();

        $paginated = new LengthAwarePaginator($result,$collection->count(),$perPage,$page,[
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }

    protected function transformData($data,$transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

//    protected function isDirty($check)
//    {
//        if(!$check->isDirty()){
//            return $this->errorResponse('You need to specify a different value to update',422);
//        }
//    }
//
//    protected function isClean($check)
//    {
//        if($check->isClean()){
//            return $this->errorResponse('You need to specify a different value to update',422);
//        }
//    }
}
