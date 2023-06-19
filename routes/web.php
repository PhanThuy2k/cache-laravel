<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::get('/', function () {
        return view('welcome');
    });
    // Route::get('demo-cache',function(){
        // sửa CACHE_DRIVER=database => xem cache trực tiếp trên web 
        // Cache::put('doman','unicode',600);
        // echo Cache::get('doman');

        // sửa CACHE_DRIVER=file => xem cache trong file stogate/framework/cache/data
        // Cache::put('doman','unicode',600); 
        // echo Cache::get('doman');
// });
    Route::get('product/{id}',[ProductController::class,'getProduct']);
    Route::get('product',[ProductController::class,'redis']);