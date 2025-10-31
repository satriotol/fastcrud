<?php

namespace Satriotol\Fastcrud\Repositories;

use Illuminate\Support\Facades\Storage;
use Satriotol\Fastcrud\Traits\RemovesFiles;
use Illuminate\Support\Str;
use Satriotol\Fastcrud\Models\FastcrudErrorLog;

class FastcrudErrorLogRepository
{
    use RemovesFiles;

    public function getAll(array $params = [], $request = null)
    {
        $query = FastcrudErrorLog::query();
        if ($request) {
            $user_id = $request->user_id;
            $method = $request->method;
            $url = $request->url;

            if ($url) {
                $query->where('url', 'like', "%$url%");
            }
            if ($user_id) {
                $query->where('user_id', $user_id);
            }
            if ($method) {
                $query->where('method', $method);
            }
        }

        if (!empty($params['filters'])) {
            foreach ($params['filters'] as $key => $value) {
                if ($value) {
                    if (str_contains($key, '.')) {
                        [$relation, $column] = explode('.', $key);
                        $query->whereHas($relation, function ($q) use ($column, $value) {
                            $q->where($column, 'like', "%$value%");
                        });
                    } else {
                        $query->where($key, 'like', "%$value%");
                    }
                }
            }
        }

        if (!empty($params['search']) && !empty($params['search_column'])) {
            $query->where($params['search_column'], 'LIKE', '%' . $params['search'] . '%');
        }

        if (!empty($params['orderBy']) && !empty($params['orderDirection'])) {
            $query->orderBy($params['orderBy'], $params['orderDirection']);
        }

        if (!empty($params['with'])) {
            $query->with($params['with']);
        }

        if (!empty($params['scope'])) {
            foreach ($params['scope'] as $scope) {
                $query->$scope();
            }
        }

        return $query;
    }
    public function search()
    {
        $query = FastcrudErrorLog::query();
        return $query;
    }
    public function validate($isUpdate = false, $id = null)
    {
        $rules = [
            'message' => 'required',
            'trace' => 'nullable',
            'error_code' => 'nullable',
            'url' => 'nullable',
            'method' => 'nullable',
            'input' => 'nullable',
            'user_id' => 'nullable',
            'ip_address' => 'nullable',
            'user_agent' => 'nullable',
            'environment' => 'nullable',
            'level' => 'required',
            'status' => 'nullable',
        ];
        if ($isUpdate) {
            if ($id) {
            }
        }
        $messages = [];
        return ['rules' => $rules, 'messages' => $messages];
    }

    public function findByUuid(string $uuid)
    {
        return FastcrudErrorLog::where('uuid', $uuid)->firstOrFail();
    }

    public function create($e)
    {
        $trace = substr($e->getTraceAsString(), 0, 5000);

        $inputData = request()->except(['password', 'token']);
        $input = substr(json_encode($inputData), 0, 2000);
        try {
            FastcrudErrorLog::create([
                'message'     => $e->getMessage(),
                'trace'       => $trace,
                'error_code'  => $e->getCode(),

                'url'         => request()->fullUrl(),
                'method'      => request()->method(),
                'input'       => $input,

                'user_id'     => auth()->id(),
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),

                'environment' => app()->environment(),
                'level'       => 'error',
                'status'      => 0,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function update(string $uuid, array $data)
    {
        $model = $this->findByUuid($uuid);



        return $model->update($data);
    }

    public function delete(string $uuid)
    {
        $model = $this->findByUuid($uuid);



        return $model->delete();
    }

    private function uploadImage($file, $name)
    {
        $envName = env('APP_NAME', 'default');

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $path = "{$envName}/FastcrudErrorLog/{$year}/{$month}/{$day}/{$name}";

        return Storage::disk('minio')->put($path, $file);
    }
}
