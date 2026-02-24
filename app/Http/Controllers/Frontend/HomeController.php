<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\SettingsSite;
use Redirect;
class HomeController extends Controller
{
    public function __construct()
    {
        $site_settings = SettingsSite::findOrFail(1)->toArray();
        $this->page_info = ['homepage_title' => $site_settings['homepage_title'], 'homepage_keywords' => $site_settings['homepage_keywords'], 'homepage_description' => $site_settings['homepage_description']];
    }
    /* Page events */
    public function home()
    {
        //$homepage_video = TemplateVideo::where('is_enabled_homepage_video', 1)->first();
        //$featured_categories = Category::where('status', 1)->where('is_featured', 1)->select('id', 'category', 'image', 'alt_text', 'sef_url')->orderBy('display_order', 'asc')->get();
        //$featured_products = [];
        
        return view('frontend.home.home')->with($this->page_info);
    }
}
