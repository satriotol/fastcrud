<?php

namespace Satriotol\Fastcrud\Repositories;

use Illuminate\Support\Facades\Auth;
use Satriotol\Fastcrud\Models\FastcrudPasswordHistory;
use Illuminate\Support\Facades\Storage;
use Satriotol\Fastcrud\Traits\RemovesFiles;
use Illuminate\Support\Facades\DB;

class FastcrudPasswordHistoryRepository
{
    use RemovesFiles;

    public function getAll(array $params = [], $request = null)
    {
        $query = FastcrudPasswordHistory::query();
        if ($request) {
        }

        return $query;
    }
    public function search()
    {
        $query = FastcrudPasswordHistory::query();
        return $query;
    }
    public function validate($isUpdate = false)
    {
        $rules = [];
        if ($isUpdate) {
        }
        $messages = [];
        return ['rules' => $rules, 'messages' => $messages];
    }

    public function findByUuid(string $uuid)
    {
        return FastcrudPasswordHistory::where('uuid', $uuid)->firstOrFail();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            FastcrudPasswordHistory::create($data);

            DB::commit();

            return [
                "status" => "success",
                "message" => "FastcrudPasswordHistory Sukses Dibuat"
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
                "message" => "FastcrudPasswordHistory Sukses Terupdate"
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
        $path = $envName . '/FastcrudPasswordHistory/' . $name;

        return Storage::disk('minio')->put($path, $file);
    }
}
