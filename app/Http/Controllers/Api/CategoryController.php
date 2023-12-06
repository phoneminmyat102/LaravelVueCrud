<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Category\AddRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::paginate(10);

        return response()->json($categories);
    }

    public function getNoPagination()
    {
        $categories = Category::latest()->get();

        return response()->json($categories);
    }

    public function add(AddRequest $request)
    {
        try {
            $validated = $request->validated();
            if($validated['status'] == 'true'){
                $validated['status'] = 1;
            }

            if($validated['status'] == 'false'){
                $validated['status'] = 0;
            }
            $imageName = NULL;
            if ($image = $validated['image']) {
                $imageUrl = $image->store('public/images');
                $validated['image'] = str_replace('public/', '', $imageUrl);
            }

            Category::create($validated);

            $success = true;
            $message = "Category created successfully";
        } catch (\Exception $error) {
            logger()->error($error);
            $success = false;
            $message = "Internal Server Error";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validated = $request->validated();
            $category = Category::find($id);

            $imageName = NULL;
            if (isset($validated['image']) && $image = $validated['image']) {
                $imageUrl = $image->store('public/images');
                $validated['image'] = str_replace('public/', '', $imageUrl);
                Storage::disk('public')->delete($category->image);
            }
            $category->update($validated);

            $success = true;
            $message = "Category updated successfully";
        } catch (\Exception $error) {
            logger()->error($error);
            $success = false;
            $message = "Internal Server Error";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function delete($id)
    {
        try{
            $category = Category::find($id);
            $category->delete();
            if(Storage::disk('public')->exists($category->image)){
                Storage::disk('public')->delete($category->image);
            }
            $success = true;
            $message = "Category deleted successfully";
        } catch (\Exception $error) {
            logger()->error($error);
            $success = false;
            $message = "Internal Server Error";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function togglePublish($id)
    {
        try{
            $category = Category::find($id);
            if($category->status){
                $category->status = 0;
            }else{
                $category->status = 1;
            }
            $category->save();

            $success = true;
            $message = "Category status updated successfully";
        } catch (\Exception $error) {
            logger()->error($error);
            $success = false;
            $message = "Internal Server Error";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
