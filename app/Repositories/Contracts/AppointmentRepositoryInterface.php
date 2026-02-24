<?php

namespace App\Repositories\Contracts;

interface AppointmentRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function deleteMultiple($ids);
    public function updateStatus($id, $status);
    public function search(array $filters);
    
    // Dependencies
    public function getDoctors();
    public function getPatients();
    public function getSymptoms();
    public function getCasualties();
    public function getTpas();
    public function getLastAppointment();
}
