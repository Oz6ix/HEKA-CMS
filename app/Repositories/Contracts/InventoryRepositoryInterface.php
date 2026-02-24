<?php

namespace App\Repositories\Contracts;

interface InventoryRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function deleteMultiple($ids);
    public function updateStatus($id, $status);
    public function checkDuplicateName($name, $id = null);
    public function getCategories();
    public function getUnits();
    public function getGenerics();
    public function getDosages();
}
