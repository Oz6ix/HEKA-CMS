<?php

namespace App\Http\Controllers\AdminModule\Imports;

use App\Brand;
use App\Category;
use App\Product;
use App\ProductDetails;
use App\ProductImage;
use App\ProductVariant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    public $response = [
        'status' => true,
        'error_log_created' => false,
        'message' => 'Products imported successfully.'
    ];
    public $header_row = [];

    // Excel file column structure, for reference
    public $header_row_format = [
        0 => "Unique SKU Code",
        1 => "L (mm)",
        2 => "B (mm)",
        3 => "H (mm)",
        4 => "Material",
        5 => "Warranty",
        6 => "Finish",
        7 => "Color",
        8 => "Remarks",
        9 => "Vendor SKU Code",
        10 => "Product",
        11 => "Configuration",
        12 => "Selling Price",
        13 => "Lead Time",
        14 => "Measurement Units",
        15 => "No of units",
        16 => "Space",
        17 => "Range",
        18 => "Business Unit",
        19 => "Segment",
        20 => "Category",
        21 => "Sub-Category",
        22 => "Class",
        23 => "Unique Code",
        24 => "Brand",
        25 => "Description",
        26 => "Production Cost",
        27 => "Variant_Material",
        28 => "Variant_Color",
        29 => "Variant_XXX",
        30 => "Variant_XXX",
        31 => "Variant_XXX",
        32 => "Main Image",
        33 => "Image 2",
        34 => "Image 3",
        35 => "Image 4",
        36 => "Image 5"
    ];

    public function collection(Collection $rows)
    {
        // An empty file or there is only header row present
        if (!isset($rows) || empty($rows) || $rows->count() <= 1) {
//            $this->response = array_merge($this->response, ['status' => false, 'message' => 'There are no products in the Excel file to import. Please correct it and try uploading the file again.']);
            return;
        }

        // Incorrect number of columns
        if (count($rows[0]) != 37) {
            $this->response = array_merge($this->response, ['status' => false, 'message' => 'There is a mismatch in the number of columns in the Excel file. Please correct it and try uploading the file again.']);
            return;
        }

        $this->header_row = $rows[0]->toArray();// header row values
        unset($rows[0]); // remove header row
        $this->create_error_log('HEADER');

        foreach ($rows as $key => $row) {
            // skip iteration if unique_sku_code, vendor_sku_code, or unique_code is null
            if ($row[0] == null || $row[9] == null || $row[23] == null)
                continue;

            // fetch the brand id
            $brand_id = $this->manage_brand($row, 24);
            if ($brand_id == null) continue;

            // fetch the category id
            $category_id = $this->manage_category($row, 21);
            if ($category_id == null) continue;

            // insert product
            $product_id = $this->manage_product($row, $category_id, $brand_id);

            // insert product variants, if available
            $product_variant = $this->manage_product_variants($row, $product_id);

            // insert product details
            $product_details_id = $this->manage_product_details($row, $category_id, $brand_id, $product_id, $product_variant);

            // insert product images, if available
            $this->manage_product_images($row, $product_id, $product_details_id, $product_variant);
        }
    }

    public function manage_brand($row, $index)
    {
        $brand = Brand::where('brand', $row[$index])->first();
        if ($brand == null) {
            $this->create_error_log('BRAND', $row->toArray(), $index);
            return;
        }

        return $brand->id;
    }

    public function manage_category($row, $index)
    {
        $category = Category::where('category', $row[$index])->first();
        if ($category == null) {
            $this->create_error_log('CATEGORY', $row->toArray(), $index);
            return;
        }

        return $category->id;
    }

    public function manage_product($row, $category_id, $brand_id)
    {
        $unique_code = $row[23];
        $data['brand_id'] = $brand_id;
        $data['category_ids'] = $category_id;
        $data['unique_sku_code'] = $row[0];
        $data['vendor_sku_code'] = $row[9];
        $data['unique_code'] = $unique_code;

        $item = Product::where('unique_code', $unique_code)
            ->where('delete_status', 0)
            ->first();

        // product does not exists
        if ($item == null)
            $item = Product::create($data);
        else
            Product::where('id', $item->id)->update($data);

        return $item->id;
    }

    public function manage_product_variants($row, $product_id)
    {
        // no variant exists
        if ($row[27] == null && $row[28] == null && $row[29] == null && $row[30] == null && $row[31] == null)
            return;

        $product_variant_values = '';
        for ($i = 27; $i <= 31; $i++) {
            $variant_name = $this->header_row[$i];
            $variant_name = explode('_', $variant_name)[1];
            $variant_value = $row[$i];

            if ($variant_value != null && !empty($variant_value)) {
                $items = ProductVariant::where('product_id', $product_id)
                    ->whereRaw('LOWER(`variant`) = ? ', [strtolower(trim($variant_name))])
                    ->get();
                $is_current_variant_exists = ($items->count() > 0);

                if ($is_current_variant_exists) {
                    // update existing variant
                    $new_variant_value = $items[0]->variant_values;

                    //if the variant value not already exists for the same product, append the new variant value
                    $pos = strpos(strtolower(trim($new_variant_value)), strtolower(trim($variant_value)));
                    if ($pos === false) {
                        $new_variant_value .= ',' . trim($variant_value);
                        ProductVariant::where('product_id', $product_id)
                            ->whereRaw('LOWER(`variant`) = ? ', [strtolower(trim($variant_name))])
                            ->update(['variant_values' => $new_variant_value]);
                    }

                    if (!empty($product_variant_values))
                        $product_variant_values .= ' / ';
                    $product_variant_values .= trim($variant_value);
                } else {
                    // new variant
                    ProductVariant::create([
                        'product_id' => $product_id,
                        'variant' => $variant_name,
                        'variant_values' => $variant_value
                    ]);

                    if (!empty($product_variant_values))
                        $product_variant_values .= ' / ';
                    $product_variant_values .= trim($variant_value);
                }
            }
        }

        return trim($product_variant_values);
    }

    public function manage_product_details($row, $category_id, $brand_id, $product_id, $product_variant)
    {
        $data['brand_id'] = $brand_id;
        $data['category_ids'] = $category_id;
        $data['title'] = ($product_variant == null) ? trim($row[10]) : trim($row[10]) . ' - (' . $product_variant . ')';
        $data['variant'] = $product_variant;
        $data['cost_price'] = $row[26];
        $data['price'] = $row[12];
        $data['quantity'] = $row[15];
        $data['length'] = $row[1];
        $data['width'] = $row[2];
        $data['height'] = $row[3];
        $data['description'] = $row[25];
        $data['sef_url'] = generate_sef_url($data['title']);
        $data['is_parent_product'] = ($product_variant == null);

        $data['unique_sku_code'] = $row[0];
        $data['material'] = $row[4];
        $data['warranty'] = $row[5];
        $data['finish'] = $row[6];
        $data['color'] = $row[7];
        $data['remarks'] = $row[8];
        $data['vendor_sku_code'] = $row[9];
        $data['configuration'] = $row[11];
        $data['lead_time'] = $row[13];
        $data['measurement_units'] = $row[14];
        $data['no_of_units'] = $row[15];
        $data['space'] = $row[16];
        $data['product_range'] = $row[17];
        $data['business_unit'] = $row[18];
        $data['segment'] = $row[19];
        $data['category'] = $row[20];
        $data['sub_category'] = $row[21];
        $data['class'] = $row[22];
        $data['unique_code'] = $row[23];

        $item = ProductDetails::where('product_id', $product_id)
            ->where('unique_sku_code', $row[0])
            ->where('vendor_sku_code', $row[9])
            ->where('delete_status', 0)
            ->first();

        if ($item == null) {
            $data['product_id'] = $product_id;
            $data['product_code'] = generate_product_code($product_id);
            $data['sku'] = generate_sku($product_id);

            $item = ProductDetails::create($data);
            $directory = 'products/' . trim($data['unique_sku_code']);
            make_directory($directory);
        } else {
            ProductDetails::where('product_id', $product_id)
                ->where('unique_sku_code', $row[0])
                ->where('vendor_sku_code', $row[9])
                ->where('delete_status', 0)
                ->update($data);
        }

        return $item->id;
    }

    public function manage_product_images($row, $product_id, $product_details_id, $product_variant)
    {
        //delete all existing image entries of this product and variant
        ProductImage::where('product_id', $product_id)
            ->where('product_details_id', $product_details_id)
            ->update(['delete_status' => 1]);

        for ($i = 32; $i <= 36; $i++) {
            if ($row[$i] != null && !empty($row[$i])) {
                $data['product_id'] = $product_id;
                $data['product_details_id'] = $product_details_id;
                $data['image'] = trim($row[$i]);
                $data['is_parent_product_image'] = ($product_variant == null);
                $product_image['image_folder'] = trim($row[0]);

                ProductImage::create($data);
            }
        }
    }

    public function create_error_log($key, $row = null, $row_index = null)
    {
        $log_content = $comments = '';
        $mode = 'a';

        switch ($key) {
            case 'HEADER':
                $mode = 'w';
                $header_row = $this->header_row;
                $header_row[37] = "Comments";
                $log_content = $header_row;
                break;
            case 'BRAND':
                $comments = 'The brand name "' . $row[$row_index] . '" is missing in the database.';
                break;
            case 'CATEGORY':
                $comments = 'The sub-category name "' . $row[$row_index] . '" is missing in the database.';
                break;
        }

        if ($mode == 'a') {
            $row[37] = $comments;
            $log_content = $row;
            $this->response = array_merge($this->response, ['error_log_created' => true]);
        }

        $file_path = base_path() . '/resources/files/downloads/products/products_import_error_log.csv';
        $log_file = fopen($file_path, $mode);
        fputcsv($log_file, $log_content, ',', '"');
        fclose($log_file);
    }
}