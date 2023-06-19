<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    const CACHE_TIME = 900;

    function getProduct($id) {

        $key = 'product' . $id;

        // Cách 1: Lưu cache
        // $product = Cache::get($key); 
        // if ($product === null) { // null lấy data ở db lưu vào bảng cache
        //     $value = product::find($id);
        //     Cache::put($key, $value, self::CACHE_TIME); // luu cache 
        //  } // Nếu đã có sẽ trả về kq data đó


        // Cách 2 : Lưu cache Không cần check null mà trực tiép lưu table cache
        // $product = Cache::get($key,function() use($id,$key){
        //     $value = Product::find($id);
        //     Cache::put($key, $value, self::CACHE_TIME); // lưu theo thời gian
        //     // Cache::forever($key, $value, self::CACHE_TIME); //Lưu cache vv
        //     return $value;
        // });


        // Cách 3 : lưu cache sử dụng remember cho get và put 
        // $product = Cache::remember($key, self::CACHE_TIME, function () use ($id,$key) {
        //     return Product::find($id);
        //     // Sử dụng rememberForever thi bỏ self::CACHE_TIME
        // });

        
        // tăng view 
        $product = Cache::rememberForever($key, function () use ($id) {
            return Product::find($id);

        });
        $product = Product::find($id); 
        $product->view_count++;  
        $product->save();

        Cache::increment('view_'.$id);
            $viewCount = Cache::remember('view_'.$id, self::CACHE_TIME, function()use($product) {
            return $product->view_count;
        });

        return view('products.detail',compact('product','viewCount'));
    }

    function redis() {
        // thêm store('địa chỉ cần lưu'), mặc định lưu vào redis khi trong .env đã cấu hình
            // $value = Cache::store('file')->remember('users', 2000, function () {
            //     return [
            //         'user 1 ',
            //     ];
            // });
        // $value = Cache::get('users'); cách 1 lấy dl cache


        // CACHE HELPER
        // $value = cache(['users'=>'123'],2000); //set dữ liệu cache 
        // cache('users'); //cách 2 lấy dữ liệu cache 
        // $data = [
        //     'user 1 ',
        //     'user 2 ',
        // ];
        // cache(['users'=>$data],Carbon::now()->addSeconds(10));//cache sẽ tồn tại trong 10s
        // $value = cache('users'); 


        // CACHE TAG //trước khi get hay đóng thể tạo cache lại để có kết quả chuẩn
        $data = [
            'user 1 ',
            'user 2 ',
        ];

        // Cache::tags(['tag1','tag2'])->put('name','phan thuy',200);
        // Cache::tags(['tag1','tag3'])->put('age','24',200);

        Cache::tags(['tag1','tag2'])->flush(); // xóa toàn bộ cache của tag đươc chỉ định

        $name = Cache::tags(['tag1','tag2'])->get('name');
        $age = Cache::tags(['tag1','tag3'])->get('age');

        

        dd($name,$age);
    }
}
