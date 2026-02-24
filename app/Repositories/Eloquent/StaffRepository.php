<?php

namespace App\Repositories\Eloquent;

use App\Models\Staff;
use App\Repositories\Contracts\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    protected $model;

    public function __construct(Staff $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->where('delete_status', 0)->with('staff_role')->orderBy('id', 'desc')->get();
    }

    public function find($id)
    {
        return $this->model->with('staff_blood_group')
                        ->with('staff_role')
                        ->with('staff_designation')
                        ->with('staff_department')
                        ->with('staff_specialist')
                        ->with('staff_document')
                        ->with('staff_group')
                        ->with(['staff_user_group' => function ($query) {
                            $query->where('delete_status', '0')
                            ->with('user_group');
                        }])->where('delete_status', 0)->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
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
        $record = $this->model->find($id);
        if ($record) {
            $record->status = $status;
            $record->save();
            return $record;
        }
        return null;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->where('delete_status', 0)->first();
    }
    
    public function findByCode($code, $id = null)
    {
        $query = $this->model->where('staff_code', $code)->where('delete_status', 0);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        return $query->first();
    }
}
