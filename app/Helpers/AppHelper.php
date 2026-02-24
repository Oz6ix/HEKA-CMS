<?php

function get_districts_by_state($state_id)
{
    $items = \App\District::where('status', 1)->where('state_id', $state_id)->orderBy('district', 'asc')->get();
    return $items;
}

function generate_otp_4_digit()
{
    // generate random 4-digit numeric passcode
    return rand(1000, 9999);
}

function generate_otp_6_digit()
{
    // generate random 6-digit numeric passcode
    return rand(100000, 999999);
}

function get_open_enquiries_count()
{
    //return \App\Enquiry::where('enquiry_status', 'Open')->count();
}

/*function generate_sku($product_id)
{
    $category_ids = \App\Product::findorFail($product_id)->toArray()['category_ids'];
    $category_id = explode(',', $category_ids)[0];
    $count = \App\ProductDetails::where('product_id', $product_id)->count() + 1;

    $code = "satt";
    $code .= '-' . $category_id;
    $code .= '-' . $product_id;
    $code .= '-' . $count;

    return $code;
}*/

/*function generate_product_code($product_id)
{
    $count = \App\ProductDetails::where('product_id', $product_id)->count() + 1;

    $code = "Staff-PRO-";
    $code .= date('d');
    $code .= date('m');
    $code .= date('y');
    $code .= '-' . $product_id;
    $code .= '-' . $count;

    return $code;
}*/

function generate_customer_code()
{
    $count = \App\Customer::count() + 1;
    $code = "SAFF-";
    $code .= date('d');
    $code .= date('m');
    $code .= date('y');
    $code .= '-' . $count;//str_pad($count, $pad_length, $pad_char);

    return $code;
}


function generate_supplier_code()
{
    //$count = \App\Models\SettingsSupplier::count() + 1;

    $code_pattern='100000';
    $code = \App\Models\SettingsSupplier::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "Supplier-";
    $result .= $code;
    //$code .= date('d');
    //$code .= date('m');
    //$code .= date('y');
    //$code .= '-' . $count;//str_pad($count, $pad_length, $pad_char);

    return $result;
}

function generate_medical_supply_code($category_id)
{
    if(\App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->count()>0){
        $code = \App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->first();
    }else{
        $code = 'IM-100000';
    }

    $code_explode = explode('-', $code, 2);
    $new_code_build =  (int)$code_explode['1'] + 1;
    
    $result = "IM-" . $new_code_build;
    return $result;
}

function generate_general_supply_code($category_id)
{
    if(\App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->count()>0){
        $code = \App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->first();
    }else{
        $code = 'IG-100000';
    }

    $code_explode = explode('-', $code, 2);
    $new_code_build =  (int)$code_explode['1'] + 1;

    $result = "IG-" . $new_code_build;;
    return $result;
}

function generate_pharmacy_supply_code($category_id)
{
    if(\App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->count()>0){
        $code = \App\Models\InventoryItemMaster::where('inventory_category_id', $category_id)->orderBy('id', 'desc')->first();
    }else{
        $code = 'IP-100000';
    }

    $code_explode = explode('-', $code, 2);
    $new_code_build =  (int)$code_explode['1'] + 1;

    $result = "IP-" . $new_code_build;;
    return $result;
}

function generate_item_master_code()
{
    //$count = \App\Models\SettingsSupplier::count() + 1;

    $code_pattern='100000';
    $code = \App\Models\InventoryItemMaster::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "IM-";
    $result .= $code;
    //$code .= date('d');
    //$code .= date('m');
    //$code .= date('y');
    //$code .= '-' . $count;//str_pad($count, $pad_length, $pad_char);
    return $result;
}


function generate_item_stock_code()
{
    //$count = \App\Models\SettingsSupplier::count() + 1;

    $code_pattern='100000';
    $code = \App\Models\InventoryStock::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "ISK-";
    $result .= $code;
    //$code .= date('d');
    //$code .= date('m');
    //$code .= date('y');
    //$code .= '-' . $count;//str_pad($count, $pad_length, $pad_char);
    return $result;
}


function generate_hospital_charge_code()
{
   
    $code_pattern='100000';
    $code = \App\Models\HospitalCharge::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "HC-";
    $result .= $code;
    return $result;
}


function generate_pharmacy_code()
{
   
    $code_pattern='000';
    $code = \App\Models\Pharmacy::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "M-";
    $result .= $code;
    return $result;
}
function generate_pathology_code()
{
   
    $code_pattern='000';
    $code = \App\Models\Pathology::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "PT-";
    $result .= $code;
    return $result;
}

function generate_radiology_code()
{
   
    $code_pattern='000';
    $code = \App\Models\Radiology::orderBy('id', 'desc')->limit(1)->get();
    $code = collect($code)->toArray(); 
    if(!empty($code)){
        $code = collect($code)->first(); 
        $code =$code['id'] + $code_pattern+1; 
    }else{
        $code = $code_pattern+1;
    } 
    $result = "RD-";
    $result .= $code;
    return $result;
}


/*function generate_enquiry_code()
{
    $count = \App\Enquiry::count() + 1;

    $code = "staff-ENQ-";
    $code .= date('d');
    $code .= date('m');
    $code .= date('y');
    $code .= '-' . $count;

    return $code;
}*/

function get_different_combinations_from_multiple_arrays($arrays, $i = 0)
{
    if (!isset($arrays[$i])) {
        return array();
    }
    if ($i == count($arrays) - 1) {
        return $arrays[$i];
    }

    // get combinations from subsequent arrays
    $temp = get_different_combinations_from_multiple_arrays($arrays, $i + 1);

    $result = array();

    // concat each array from temp with each element from $arrays[$i]
    foreach ($arrays[$i] as $v) {
        foreach ($temp as $t) {
            $result[] = is_array($t) ? array_merge(array($v), $t) : array($v, trim($t));
        }
    }

    return $result;
}

function verify_file_mime_type($file, $file_type)
{
    $mime_type = $file->getMimeType();
    switch ($file_type) {
        case 'image':
            return ($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/gif');
        case 'pdf':
            return ($mime_type == 'application/pdf');
        case 'video':
            return ($mime_type == 'video/mp4');
        case 'special':
        return ($mime_type == 'application/pdf' || $mime_type == 'application/msword' || $mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $mime_type == 'application/vnd.ms-powerpoint');

        default:
            return false;
    }
}






function save_image_with_new_dpi($image, $directory, $delete_existing_file = false, $file_name = null)
{
    $mime_type = $image->getMimeType();
    switch ($mime_type) {
        case 'image/png':
            ob_start();
            $file = imagecreatefrompng($image);
            break;
        case 'image/gif':
            ob_start();
            $file = imagecreatefromgif($image);
            break;
        case 'image/jpeg':
        case 'image/jpg':
            ob_start();
            $file = imagecreatefromjpeg($image);
            break;
    }

    $path = base_path() . '/resources/files/uploads/' . $directory . '/';
    // if a static filename is not specified
    if ($file_name == null) {
        $file_name = $image->getClientOriginalName();
        $file_name = preg_replace('/[ ,]+/', '', $file_name);
    } else
        $file_name .= '.' . get_file_extension($image);

    if ($delete_existing_file)
        delete_file($file_name, $directory); // delete existing file with the filename
    else {
        // file name already exists, create a new filename
        if (file_exists($path . $file_name))
            $file_name = time() . "-" . $file_name;
    }

    $img = new Imagick();
    imagejpeg($file, NULL, 90);//quality = 0(max compression) - 100(min compression)
    $data = ob_get_contents();
    ob_end_clean();

    $img->readimageblob($data);
    $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
    $img->setImageResolution(100, 100);
    $img->writeImage($path . $file_name);
    //dd($img->getImageResolution());

    return $file_name;


//    $decoded = base64_decode($image);
//    $image1 = new Imagick(file_get_contents($image));
//    $resolutions = $image1->getImageResolution();
//    dd($resolutions);

//    $string = imagecreatefromjpeg($image);
//
//    $data = bin2hex(substr($string,14,4));
//    $x = substr($data,0,4);
//    $y = substr($data,4,4);
//
//    $image_dpi_array[0] = $x;
//    $image_dpi_array[1] = $y;

//    $image_data = \Intervention\Image\Facades\Image::make($image)->exif();
//    dd($image_data);
//    if ($image_data == null || !isset($image_data['XResolution']))
//        return false;
//
//    $temp_array = explode('/', $image_data['XResolution']);
//    $image_dpi_array[0] = $temp_array[0] / $temp_array[1];
//    $temp_array = explode('/', $image_data['YResolution']);
//    $image_dpi_array[1] = $temp_array[0] / $temp_array[1];
//dd($image_dpi_array);
//    return (($image_dpi_array[0] >= 72 && $image_dpi_array[0] <= 300) && ($image_dpi_array[1] >= 72 && $image_dpi_array[1] <= 300));
}

function validate_image_dimension($image, $valid_width, $valid_height)
{
    $image_details = getimagesize($image);
    $image_width = $image_details[0];
    $image_height = $image_details[1];
    //$ratio = $image_width / $image_height;
    return ($image_width >= $valid_width && $image_height >= $valid_height);
}

function validate_file_size($file, $valid_file_size)
{
    $size = $file->getSize();// file size in bytes

    //dd($size);
    return ($size <= $valid_file_size);
}


function upload_file($file, $directory, $delete_existing_file = false, $file_name = null)
{
    // if a static filename is not specified
    if ($file_name == null) {
        $file_name = $file->getClientOriginalName();
        $file_name = preg_replace('/[ ,]+/', '', $file_name);
    } else
        $file_name .= '.' . get_file_extension($file);

    if ($delete_existing_file) delete_file($file_name, $directory); // delete existing file with the filename
    else {
        // file name already exists, create a new filename
        if (\Storage::disk('public_uploads')->exists($directory . '/' . $file_name)) $file_name = time() . "-" . $file_name;
    }

    \Storage::disk('public_uploads')->putFileAs($directory, $file, $file_name);

    return $file_name;
}

function delete_file($file_name, $directory)
{
    if (\Storage::disk('public_uploads')->exists($directory . '/' . $file_name)) {
        \Storage::disk('public_uploads')->delete($directory . '/' . $file_name);
    }
    return true;
}

// compress and convert file to base64 format and return
function get_base64_file($file)
{
    $mime_type = $file->getMimeType();
    switch ($mime_type) {
        case 'video/mp4':// iOS video format
            // save in database
            $data = file_get_contents($file);
            $mime_type = 'video/mp4';
            $base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);
            return $base64;
            break;
        case 'application/octet-stream':// Android video format
            $data = file_get_contents($file);
            $mime_type = 'video/mp4';
            $base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);
            return $base64;
            break;
        case 'audio/x-hx-aac-adts':// Android audio format
            $data = file_get_contents($file);
            $mime_type = 'audio/mpeg';
            $base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);
            return $base64;
            break;
        case 'audio/mpeg':// iOS audio format
            $data = file_get_contents($file);
            $base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);
            return $base64;
            break;
        case 'image/png':
            ob_start();
            $file = imagecreatefrompng($file);
            //imagepng($file, NULL, 3);
            break;
        case 'image/gif':
            ob_start();
            $file = imagecreatefromgif($file);
            //imagegif($file, NULL, 40);
            break;
        case 'image/jpeg':
        case 'image/jpg':
            ob_start();
            $file = imagecreatefromjpeg($file);
            //imagejpeg($file, NULL, 40);
            break;
    }

    imagejpeg($file, NULL, 40);//quality = 0(max compression) - 100(min compression)
    $data = ob_get_contents();
    ob_end_clean();
    $mime_type = 'image/jpeg';
    $base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);

    return $base64;
}

// get the file mime type and return
function get_file_mime_type($file)
{
    $mime_type = $file->getMimeType();
    return $mime_type;
}

// get the file extension and return
function get_file_extension($file)
{
    $file_name = $file->getClientOriginalName();
    $array = explode('.', $file_name);
    $extension = end($array);

    return $extension;
}

// encrypt a string and return
function get_encrypted_string($string)
{
    return encrypt($string);
}

// decrypt a string and return
function get_decrypted_string($string)
{
    return decrypt($string);
}

// create directory
function make_directory($directory)
{
    if (!\Storage::disk('public_uploads')->exists($directory)) {
        \Storage::disk('public_uploads')->makeDirectory($directory);
    }
}

function generate_sef_url($input, $replace = '-', $remove_words = true)
{
    $words_array = array('a', 'and', 'the', 'an', 'it', 'is', 'with', 'can', 'of', 'why', 'not');

    //make it lowercase, remove punctuation, remove multiple/leading/ending spaces
    $sef_url = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input)));


     //remove words, if not helpful to seo
    //i like my defaults list in remove_words(), so I wont pass that array
    if ($remove_words) {
        $sef_url = remove_words($sef_url, $replace, $words_array);
    }

    //replace the spaces with $replace value
    $sef_url = str_replace(' ', $replace, $sef_url);

    //replace the double $replace value with single $replace value, if any
    $sef_url = str_replace($replace . $replace, $replace, $sef_url);

    return $sef_url;
}

/* takes an input, scrubs unnecessary words */
function remove_words($input, $replace, $words_array = [], $unique_words = true)
{
    //separate all words based on spaces
    $input_array = explode(' ', $input);

    //create the return array
    $return = array();

    //loops through words, remove bad words, keep good ones
    foreach ($input_array as $word) {
        //if it's a word we should add...
        if (!in_array($word, $words_array) && ($unique_words ? !in_array($word, $return) : true)) {
            $return[] = $word;
        }
    }

    //return good words separated by dashes
    return implode($replace, $return);
}

function generate_log($action, $record_id = null, $notes = null)
{
    $data['ip_address'] = get_user_ip_address();
    $data['admin_id'] = (\Session::has('auth_user_id')) ? \Session::get('auth_user_id') : 0;
    $data['record_id'] = $record_id;
    $data['action'] = $action;
    $data['notes'] = $notes;

    if (\Session::get('auth_group_id') == 'SERVICE') \App\LogServiceHub::create($data); elseif (\Session::get('auth_group_id') == 'FRANCHISE') \App\LogFranchiseHub::create($data);
    elseif (\Session::get('auth_group_id') == 'BOTH') \App\LogServiceHub::create($data);
    else
        \App\Models\Log::create($data);
}

function get_user_ip_address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
