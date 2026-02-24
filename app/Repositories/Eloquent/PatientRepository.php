<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface
{
    protected $model;

    public function __construct(Patient $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->where('delete_status', 0)->orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return $this->model->where('delete_status', 0)->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        if ($record) {
            $record->update(['delete_status' => 1]);
            return true;
        }
        return false;
    }

    public function deleteMultiple(array $ids)
    {
         return $this->model->whereIn('id', $ids)->update(['delete_status' => 1]);
    }

    public function updateStatus($id, $status)
    {
        $record = $this->find($id);
        if ($record) {
            $record->status = $status;
            $record->save();
            return $record;
        }
        return null;
    }
    
    public function exists($data, $id = null)
    {
        $query = $this->model->where('delete_status', 0);
        
        foreach ($data as $key => $value) {
            $query->where($key, $value);
        }

        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->exists();
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->where('delete_status', 0)->first();
    }
    
    public function findByCode($code)
    {
        return $this->model->where('patient_code', $code)->where('delete_status', 0)->first();
    }
}
