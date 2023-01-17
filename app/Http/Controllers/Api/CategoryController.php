<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Api
{
    /**
     * @OA\Get(
     * path="/api/category/get",
     * summary="get",
     * description="category create",
     * operationId="categoryget",
     * tags={"User"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function get(Request $request) {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $query = Category::select('id','parent_id' ,'category','slug' ,'is_parent')
                 ->where('parent_id' ,$parent_id)
                 ->where('status', 'active');
        if($request->search != '' && $parent_id == 0){
           $query->where('category' , 'ilike' , "%".trim($request->search)."%");
        }
        $data = $query->orderBy('category' , 'ASC')
        ->groupBy('id','parent_id' ,'category','slug' ,'is_parent')->get();
        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     * path="/api/menu/category",
     * summary="get",
     * description="category menu",
     * operationId="category",
     * tags={"User"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Category Menu",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

     public function menu_category(){
        $menu = Category::select('icon' ,'category','slug','id')->where('status', 'active')->where('show_in_menu' , true)->get();
        return $this->successResponse($menu);
     }


}
