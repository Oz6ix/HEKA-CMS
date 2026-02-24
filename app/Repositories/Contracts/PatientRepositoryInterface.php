<?php

namespace App\Repositories\Contracts;

interface PatientRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function deleteMultiple(array $ids);
    public function updateStatus($id, $status);
    public function exists($data, $id = null);
    public function findByEmail($email);
    public function findByCode($code);
}
