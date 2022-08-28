<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return ResponseFormatter::success($categories, 'categories retrieved');
    }

    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return ResponseFormatter::success($category, 'category created');
    }

    public function show($id){
        $category = Category::find($id);

        if($category){
            return ResponseFormatter::success($category, 'Category found');
        }
        return ResponseFormatter::error('Category not found', null, 404);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::find($id);
        if($category){
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return ResponseFormatter::success($category, 'Category updated');
        }
        return ResponseFormatter::error('Category not found', null, 404);
    }

    public function delete($id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            return ResponseFormatter::success($category, 'Category deleted');
        }
        return ResponseFormatter::error('Category not found', null, 404);
    }
}
