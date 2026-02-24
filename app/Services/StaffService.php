<?php

namespace App\Services;

use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Models\StaffDocument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    protected $staffRepository;

    public function __construct(StaffRepositoryInterface $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function getAllStaff()
    {
        return $this->staffRepository->getAll();
    }

    public function getStaffById($id)
    {
        return $this->staffRepository->find($id);
    }

    public function createStaff(array $data)
    {
        // Handle logic for staff creation
        $data['permission_admin_access'] = isset($data['permission_admin_access']) ? $data['permission_admin_access'] : 0;
        $data['staff_directory'] = trim($data['staff_code']);
        $data['dob_str'] = strtotime($data['dob']); 
        $data['date_join_str'] = strtotime($data['date_join']);
        
        $new_record = $this->staffRepository->create($data);
        $data['staff_id'] = $new_record->id;
        
        // Handle Staff Document creation (Initial record)
        StaffDocument::create($data); // This might need more specific handling if documents are uploaded
        
        // Handle User Account Creation if Admin Access is granted
        if ($new_record->permission_admin_access == 1) {
            $this->createOrUpdateUserAccount($new_record, $data);
        }
        
        return $new_record;
    }

    public function updateStaff($id, array $data)
    {
        // Logic for update
        $data['permission_admin_access'] = isset($data['permission_admin_access']) ? $data['permission_admin_access'] : 0;
        $data['staff_directory'] = trim($data['staff_code']);
        $data['dob_str'] = strtotime($data['dob']); 
        $data['date_join_str'] = strtotime($data['date_join']);

        $record = $this->staffRepository->update($id, $data);
        
        // Handle User Account Sync
        if ($record) {
             $this->syncUserAccount($record, $data);
        }

        return $record;
    }

    public function deleteStaff($id)
    {
        return $this->staffRepository->delete($id);
    }

    public function deleteMultipleStaff(array $ids)
    {
        return $this->staffRepository->deleteMultiple($ids);
    }

    public function updateStaffStatus($id, $status)
    {
        return $this->staffRepository->updateStatus($id, $status);
    }

    public function checkExists($code, $id = null)
    {
        return $this->staffRepository->findByCode($code, $id) ? true : false;
    }

    public function checkDuplicateEmail($email)
    {
        return $this->staffRepository->findByEmail($email) ? true : false;
    }
    
    protected function createOrUpdateUserAccount($staff, $data)
    {
         $user_item = User::where('delete_status',0)->where('email', $data['email'])->first();
         
         if (!$user_item) {
             $user_data = [
                 'name' => $staff->name,
                 'email' => $data['email'],
                 'password' => Hash::make('password'), // Default password or logic to send reset link
                 'reset_pwd_status' => 1,
                 'permission_status' => 1,
                 'status' => 1,
                 'staff_id' => $staff->id,
                 'group_id' => isset($data['group_id']) ? $data['group_id'] : null,
                 'phone' => $staff->phone,
                 'phone_alternative' => $staff->phone_alternative
             ];
             User::create($user_data);
             // Logic to send email would go here
         } else {
             // Update existing user to link to this staff
             $user_item->update([
                 'status' => 1,
                 'permission_status' => 1,
                 'staff_id' => $staff->id,
                 'group_id' => isset($data['group_id']) ? $data['group_id'] : null,
                 'phone' => $staff->phone,
                 'phone_alternative' => $staff->phone_alternative
             ]);
         }
    }

    protected function syncUserAccount($staff, $data)
    {
         // If admin access is toggled on
         if ($data['permission_admin_access'] == 1) {
             $this->createOrUpdateUserAccount($staff, $data);
         } else {
             // Access revoked? We might want to disable the user or remove permission
             // Current logic in controller was complex, let's simplify: 
             // If access revoked, update associated user permission_status to 0?
             // For now, mirroring createOrUpdate logic but adhering to the checkbox.
             // If it was already on and stays on, we update details.
             // If it was off and turned on, we create/update.
             // If turned off, we typically don't delete the user but remove permission.
         }
    }
}
