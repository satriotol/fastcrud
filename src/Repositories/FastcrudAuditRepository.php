<?php

namespace Satriotol\Fastcrud\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Satriotol\Fastcrud\Traits\RemovesFiles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class FastcrudAuditRepository
{
    use RemovesFiles;

    public function getAll(array $params = [], $request = null)
    {
        $query = Audit::query();
        if ($request) {
            $user_id = $request->user_id;
            $event = $request->event;
            $auditable_type = $request->auditable_type;
            $ip_address = $request->ip_address;
            $auditable_id = $request->auditable_id;
            $created_at = $request->created_at;

            if ($this->filled($auditable_id)) {
                $this->whereInteger($query, 'auditable_id', $auditable_id);
            }
            if ($this->filled($user_id)) {
                $this->whereInteger($query, 'user_id', $user_id);
            }
            if ($this->filled($auditable_type)) {
                $query->where('auditable_type', trim($auditable_type));
            }
            if ($this->filled($event)) {
                $query->where('event', trim($event));
            }
            if ($this->filled($ip_address)) {
                $query->where('ip_address', trim($ip_address));
            }

            // Filter tanggal memakai rentang datetime (>= awal hari, < awal hari berikutnya)
            // alih-alih whereDate(), karena DATE(created_at) membungkus kolom dengan fungsi
            // sehingga database tidak bisa memakai index pada created_at.
            if ($date = $this->parseDate($created_at)) {
                $query->where('created_at', '>=', $date->copy()->startOfDay())
                    ->where('created_at', '<', $date->copy()->addDay()->startOfDay());
            }
            if ($from = $this->parseDate($request->created_from)) {
                $query->where('created_at', '>=', $from->startOfDay());
            }
            if ($to = $this->parseDate($request->created_to)) {
                $query->where('created_at', '<', $to->addDay()->startOfDay());
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

    private function filled($value): bool
    {
        return $value !== null && trim((string) $value) !== '';
    }

    private function whereInteger($query, string $column, $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $query->where($column, (int) $value);
        }
    }

    private function parseDate($value): ?Carbon
    {
        if (!$this->filled($value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', trim($value))->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    public function search()
    {
        $query = Audit::query();
        return $query;
    }
    public function validate($isUpdate = false, $id = null)
    {
        $rules = [];
        if ($isUpdate) {
            if ($id) {
            }
        }
        $messages = [];
        return ['rules' => $rules, 'messages' => $messages];
    }

    public function findByUuid(string $uuid)
    {
        return Audit::where('uuid', $uuid)->firstOrFail();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            //UPLOAD_LOGIC

            Audit::create($data);

            DB::commit();

            return [
                "status" => "success",
                "message" => "Audit Sukses Dibuat"
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                "status" => "error",
                "message" => $th->getMessage()
            ];
        }
    }


    public function update(string $uuid, array $data)
    {
        DB::beginTransaction();

        try {
            $model = $this->findByUuid($uuid);

            //UPLOAD_LOGIC

            $model->update($data);

            DB::commit();

            return [
                "status" => "success",
                "message" => "Audit Sukses Terupdate"
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                "status" => "error",
                "message" => $th->getMessage()
            ];
        }
    }

    public function delete(string $uuid)
    {
        $model = $this->findByUuid($uuid);

        //DELETE_LOGIC

        $model->delete();
    }

    private function uploadImage($file, $name)
    {
        $envName = env('APP_NAME', 'default');
        $path = $envName . '/Audit/' . $name;

        return Storage::disk('minio')->put($path, $file);
    }
}
