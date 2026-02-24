<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Models\InventoryItemMaster;
use App\Models\InventoryCategory;
use App\Models\Units;
use App\Models\PharmacyGeneric;
use App\Models\PharmacyDosage;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function all()
    {
        return InventoryItemMaster::where('delete_status', 0)
            ->with(['inventory_category', 'unit'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function find($id)
    {
        return InventoryItemMaster::findOrFail($id);
    }

    public function create(array $data)
    {
        return InventoryItemMaster::create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        return InventoryItemMaster::where('id', $id)->update(['delete_status' => 1]);
    }

    public function deleteMultiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    $this->delete($id);
                }
            }
            return true;
        }
        return false;
    }

    public function updateStatus($id, $status)
    {
        return InventoryItemMaster::where('id', $id)->update(['status' => $status]);
    }

    public function checkDuplicateName($name, $id = null)
    {
        if ($id == null) {
            return InventoryItemMaster::where('item_name', $name)->where('delete_status', 0)->get();
        } else {
            return InventoryItemMaster::where('item_name', $name)->where('id', '!=', $id)->where('delete_status', 0)->get();
        }
    }

    public function getCategories()
    {
        return InventoryCategory::where('delete_status', 0)
            ->orderBy('inventory_name', 'asc')
            ->get();
    }

    public function getUnits()
    {
        return Units::where('delete_status', 0)
            ->orderBy('unit', 'asc')
            ->get();
    }

    public function getGenerics()
    {
        return PharmacyGeneric::where('del_status', 0)
            ->orderBy('generic', 'ASC')
            ->get();
    }

    public function getDosages()
    {
        return PharmacyDosage::where('del_status', 0)
            ->orderBy('dosage', 'ASC')
            ->get();
    }
}
