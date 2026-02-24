<?php
function fetch_header_data()
{
    $logo_name = \App\Models\SettingSiteLogo::findOrFail(1)->toArray();
    $site_settings = \App\Models\SettingsSiteGeneral::findOrFail(1)->toArray();
    $header_data = ['directory_logos' => "logos", 'logo_name' => $logo_name, 'site_settings' => $site_settings];
    return $header_data;
}





function fetch_header_banner()
{
    $banner_name = \App\TemplateBannerElement::findOrFail(1)->toArray(); 
    $banner_name_default = \App\TemplateBannerElement::findOrFail(1)->toArray()['default_image_1']; 
    $banner_name_about = \App\TemplateBannerElement::findOrFail(1)->toArray()['about_image_2']; 
    $banner_name_contact = \App\TemplateBannerElement::findOrFail(1)->toArray()['contact_image_3']; 

    $banner_name_faq = \App\TemplateBannerElement::findOrFail(1)->toArray()['faq_image']; 
    $banner_name_news = \App\TemplateBannerElement::findOrFail(1)->toArray()['news_image']; 
    $banner_name_forum = \App\TemplateBannerElement::findOrFail(1)->toArray()['forum_image']; 
    $banner_name_team = \App\TemplateBannerElement::findOrFail(1)->toArray()['team_image']; 
    $banner_name_course = \App\TemplateBannerElement::findOrFail(1)->toArray()['course_image']; 


    $header_banner_data = ['directory_banner' => "banner", 'banner_name_default' => $banner_name_default, 'banner_name_about' => $banner_name_about,'banner_name_contact' => $banner_name_contact ,'banner_name_faq' => $banner_name_faq,'banner_name_news' => $banner_name_news,'banner_name_team' => $banner_name_team,'banner_name_course' => $banner_name_course,'banner_name_forum' => $banner_name_forum];
    return $header_banner_data;
}



function generate_homepage_slider()
{
    $homepage_slider = '';
    $directory_sliders = "sliders";
    $sliders = \App\TemplateSlider::where('status', 1)->get(); 


    if (isset($sliders) && $sliders->count() > 0) {
        $homepage_slider .= '<div class="main-banner header-text" id="top">';
        $homepage_slider .= '<div class="Modern-Slider">';
        foreach ($sliders as $key => $slider) { 
            $homepage_slider .= '<div class="item"> ';

            $homepage_slider .= '<div class="img-fluid img-fill" style="background-image:url(' .URL::asset('resources/files/uploads/' . $directory_sliders . '/' . $slider['slider_image']). ');background-repeat:background-repeat;background-position:center center;background-size: cover">';
            if(!empty($slider['title']) || !empty($slider['sub_title']) || !empty($slider['sub_title_note'])){
            $homepage_slider .= '<div class="text-content">';            
            $homepage_slider .= '<h6>'.$slider['title'].'</h6>';
            $homepage_slider .= '<h4>'.$slider['sub_title'].'</h4>';
            $homepage_slider .= '<p class="pb-3">'.$slider['sub_title_note'].'</p>';
                if($slider['button_type']==1){
                 $homepage_slider .= '<a href="'.$slider['url'].'" class="view-button">View Courses</a>';
                }else if($slider['button_type']==2){
                 $homepage_slider .= '<a href="'.$slider['url'].'" class="view-button">Contact Us</a>';
                }else if($slider['button_type']==3){
                 $homepage_slider .= '<a href="'.$slider['url'].'" class="view-button">Read News</a>';
               }else{ 
               }  
            }  
            $homepage_slider .= '</div>';
            $homepage_slider .= '</div>';
            $homepage_slider .= '</div>';           

        }
        $homepage_slider .= '</div>';
        $homepage_slider .= '</div>';
    }
    //dd($homepage_slider);
    $file_path = base_path() . '/resources/views/frontend/home/includes/homepage_slider_section.blade.php';
    file_put_contents($file_path, $homepage_slider);
}



function generate_homepage_elements()
{
    $homepage_elements = '';
    $directory_homepage_elements = "homepage_elements";
    $item_homepage_elements = \App\TemplateHomepageElement::findOrFail(1)->toArray();
    if (!empty($item_homepage_elements)) {
        $homepage_elements .= '<div class="services-area wrapper-padding-4 gray-bg pt-80 pb-80">';
        $homepage_elements .= '<div class="container-fluid">';
        $homepage_elements .= '<div class="shipping">';
        $homepage_elements .= '<div class="row">';
        $homepage_elements .= '<div class="col-md-4">';
        $homepage_elements .= '<a href="' . $item_homepage_elements['url_1'] . '">';
        $homepage_elements .= '<div class="iconbox">';
        $homepage_elements .= '<div class="iconbox-inner">';
        $homepage_elements .= '<div class="icon">';
        $homepage_elements .= '<span class="flaticon-package">';
        $homepage_elements .= '<img src="' . URL::asset('resources/files/uploads/' . $directory_homepage_elements . '/' . $item_homepage_elements['image_1']) . '" alt="' . $item_homepage_elements['title_1'] . '"></span>';
        $homepage_elements .= '</span>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '<div class="content">';
        $homepage_elements .= '<h4 class="title">' . $item_homepage_elements['title_1'] . '</h4>';
        $homepage_elements .= '<div class="desc">' . $item_homepage_elements['sub_title_1'] . '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</a>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '<div class="col-md-4">';
        $homepage_elements .= '<a href="' . $item_homepage_elements['url_2'] . '">';
        $homepage_elements .= '<div class="iconbox">';
        $homepage_elements .= '<div class="iconbox-inner">';
        $homepage_elements .= '<div class="icon">';
        $homepage_elements .= '<span class="flaticon-package">';
        $homepage_elements .= '<img src="' . URL::asset('resources/files/uploads/' . $directory_homepage_elements . '/' . $item_homepage_elements['image_2']) . '" alt="' . $item_homepage_elements['title_2'] . '"></span>';
        $homepage_elements .= '</span>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '<div class="content">';
        $homepage_elements .= '<h4 class="title">' . $item_homepage_elements['title_2'] . '</h4>';
        $homepage_elements .= '<div class="desc">' . $item_homepage_elements['sub_title_2'] . '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</a>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '<div class="col-md-4">';
        $homepage_elements .= '<a href="' . $item_homepage_elements['url_3'] . '">';
        $homepage_elements .= '<div class="iconbox">';
        $homepage_elements .= '<div class="iconbox-inner">';
        $homepage_elements .= '<div class="icon">';
        $homepage_elements .= '<span class="flaticon-package">';
        $homepage_elements .= '<img src="' . URL::asset('resources/files/uploads/' . $directory_homepage_elements . '/' . $item_homepage_elements['image_3']) . '" alt="' . $item_homepage_elements['title_3'] . '"></span>';
        $homepage_elements .= '</span>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '<div class="content">';
        $homepage_elements .= '<h4 class="title">' . $item_homepage_elements['title_3'] . '</h4>';
        $homepage_elements .= '<div class="desc">' . $item_homepage_elements['sub_title_3'] . '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</a>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
        $homepage_elements .= '</div>';
    }
    $file_path = base_path() . '/resources/views/frontend/home/includes/homepage_elements_section.blade.php';
    file_put_contents($file_path, $homepage_elements);
}

function shopping_cart_header_helper()
{
    $Authuser = Auth::user();
    $cart_items = \App\ShoppingCart::with([
        'product_details' => function ($query) {
            $query->with([
                'product_image' => function ($query) {
                    $query->where('delete_status', '0')
                        ->select('id', 'product_details_id', 'image', 'image_folder');
                }])
                ->where('status', '1')
                ->where('delete_status', '0')
                ->select('id', 'id as product_details_id', 'variant', 'title', 'price', 'display_price', 'quantity', 'stock_status');
        }])
        ->where('customer_id', $Authuser['id'])
        ->where('cart_status', 'Active')// Currently active cart
        ->select('id AS cart_item_id', 'product_details_id', 'quantity')
        ->get();
    $cart_items = collect($cart_items)->toArray();
    return $cart_items;
}
function shopping_cart_header_count_helper()
{
    $Authuser = Auth::user();
    $cart_items = \App\ShoppingCart::with([
        'product_details' => function ($query) {
            $query->with([
                'product_image' => function ($query) {
                    $query->where('delete_status', '0')
                        ->select('id', 'product_details_id', 'image', 'image_folder');
                }])
                ->where('status', '1')
                ->where('delete_status', '0')
                ->select('id', 'id as product_details_id', 'variant', 'title', 'price', 'display_price', 'quantity', 'stock_status');
        }])
        ->where('customer_id', $Authuser['id'])
        ->where('cart_status', 'Active')// Currently active cart
        ->select('id AS cart_item_id', 'product_details_id', 'quantity')
        ->count();
    //$cart_items=collect($cart_items)->toArray();
    return $cart_items;
}


function fetch_contact_info()
{
    $contact_info = \App\SettingsContactInfo::findOrFail(1)->toArray();
    return $contact_info;
}



?>
