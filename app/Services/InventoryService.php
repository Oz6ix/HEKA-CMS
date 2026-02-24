<?php

namespace App\Services;

use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class InventoryService
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function getAllItems()
    {
        return $this->inventoryRepository->all();
    }

    public function getItemById($id)
    {
        return $this->inventoryRepository->find($id);
    }

    public function createItem(array $data)
    {
        $category_id = $data['inventory_category_id'];
        
        switch($category_id) {
            case('1'):
                $data['master_code'] = generate_medical_supply_code($category_id); 
                break;
            case('2'):
                $data['master_code'] = generate_general_supply_code($category_id); 
                break;
            case('3'):
                $data['master_code'] = generate_pharmacy_supply_code($category_id);
                break;
            default:
                // Fallback or error handling if needed, though validation should catch invalid categories
                break;
        }

        return $this->inventoryRepository->create($data);
    }

    public function updateItem($id, array $data)
    {
        if(isset($data['inventory_category_id']) && $data['inventory_category_id'] != 3) {
            $data['pharmacy_generic'] = 0;
            $data['pharmacy_dosage'] = 0;
            $data['route'] = "";
        }
        
        return $this->inventoryRepository->update($id, $data);
    }

    public function deleteItem($id)
    {
        return $this->inventoryRepository->delete($id);
    }

    public function deleteMultipleItems($ids)
    {
        return $this->inventoryRepository->deleteMultiple($ids);
    }

    public function activateItem($id)
    {
        return $this->inventoryRepository->updateStatus($id, 1);
    }

    public function deactivateItem($id)
    {
        return $this->inventoryRepository->updateStatus($id, 0);
    }

    public function checkDuplicateName($name, $id = null)
    {
        $items = $this->inventoryRepository->checkDuplicateName($name, $id);
        return $items->count() > 0 ? 1 : 0;
    }
    
    public function getDependencyData()
    {
        return [
            'categories' => $this->inventoryRepository->getCategories(),
            'units' => $this->inventoryRepository->getUnits(),
            'generics' => $this->inventoryRepository->getGenerics(),
            'dosages' => $this->inventoryRepository->getDosages(),
        ];
    }

    public function importItems($file)
    {
        $tmpName = $file->getRealPath();
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($tmpName);
        
        $count = 0;
        
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // Skip header row
                if($count == 0){
                    $count++;
                    continue;
                }
                
                $cells = $row->getCells();
                
                // Assuming cells index based on the controller logic: 
                // 1: item_name, 2: category_name, 3: unit_name, 4: description
                
                // Note: Spout cells are 0-indexed in array but getCells() returns array of Cell objects
                // We need to access values. 
                // The original code accessed $cells[1], $cells[2]... implying 1-based index or specific column mapping
                // Let's adapt based on observation. Spout getCells returns array, likely 0-indexed.
                // If original code was $cells[1], it might be skipping a leading empty cell or index column.
                
                // Let's safely extract values
                $values = [];
                foreach ($cells as $cell) {
                    $values[] = $cell->getValue();
                }
                
                // Mapping based on previous controller code:
                // $cells[1] -> Item Name
                // $cells[2] -> Category Name
                // $cells[3] -> Unit Name
                // $cells[4] -> Description

                if(isset($values[1]) && isset($values[2]) && isset($values[3]) &&
                   !empty($values[1]) && !empty($values[2]) && !empty($values[3])) {
                    
                    $itemName = $values[1];
                    $categoryName = $values[2];
                    $unitName = $values[3];
                    $description = isset($values[4]) ? $values[4] : '';
                    
                    // Find Category ID
                    $category = \App\Models\InventoryCategory::where('inventory_name', $categoryName)
                        ->where('status', 1)
                        ->where('delete_status', 0)
                        ->first();
                        
                    // Find Unit ID
                    $unit = \App\Models\Units::where('unit', $unitName)
                        ->where('status', 1)
                        ->where('delete_status', 0)
                        ->first();
                        
                    if (!$category || !$unit) {
                        // In a real service, we might throw exception or collect errors
                        // For detailed feedback, we could return a result object
                        // For now, continuing to next row or returning error
                        continue; 
                    }
                    
                    $data = [
                        'item_name' => $itemName,
                        'inventory_category_id' => $category->id,
                        'inventory_unit' => $unit->id,
                        'description' => $description,
                        'status' => 1,
                        'delete_status' => 0
                    ];
                    
                    // Check existence
                    $existing = $this->inventoryRepository->checkDuplicateName($itemName);
                    
                    if ($existing->count() == 0) {
                        $this->createItem($data);
                    } else {
                        // Update existing
                        $this->updateItem($existing->first()->id, $data);
                    }
                }
            }
        }
        
        $reader->close();
        return true;
    }

    public function exists($name, $id = null)
    {
         return $this->inventoryRepository->checkDuplicateName($name, $id);
    }
}
