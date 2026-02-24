<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class ImagesController
 * @package App\Http\Controllers\AdminModule
 */


class ImagesController extends Controller {
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($user_id, $slug)
    {
    	$slug='p4ux4sz53IOfWijdqikW.png';
        $storagePath = storage_path('uploads/' . $user_id . '/' . $slug);
        return Image::make($storagePath)->response();
    }
}