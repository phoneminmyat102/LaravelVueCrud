<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\Item\AddRequest;
use App\Http\Requests\Item\UpdateRequest;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::paginate(10);

        $items->getCollection()->transform(function ($item) {
            $item->category = $item->category->name;
            return $item;
        });

        return response()->json($items);
    }

    public function add(AddRequest $request)
    {
        try{
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

            Item::create($validated);

            $success = true;
            $message = "Item created successfully";
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
        $item = Item::find($id);
        return response()->json($item);
    }

    public function update($id, UpdateRequest $request)
    {
        try{
            $validated = $request->validated();
            $item = Item::find($id);

            $imageName = NULL;
            if (isset($validated['image']) && $image = $validated['image']) {
                $imageUrl = $image->store('public/images');
                $validated['image'] = str_replace('public/', '', $imageUrl);
                Storage::disk('public')->delete($item->image);
            }

            $item->update($validated);

            $success = true;
            $message = "Item updated successfully";
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
        try {
            $item = Item::find($id);
            $item->delete();
            if(Storage::disk('public')->exists($item->image)){
                Storage::disk('public')->delete($item->image);
            }

            $success = true;
            $message = "Item deleted successfully";
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
            $item = Item::find($id);
            if($item->status){
                $item->status = 0;
            }else{
                $item->status = 1;
            }
            $item->save();

            $success = true;
            $message = "Item status updated successfully";
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
