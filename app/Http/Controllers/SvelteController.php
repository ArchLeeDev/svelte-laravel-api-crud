<?php

namespace App\Http\Controllers;

use App\Models\Svelte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class SvelteController extends Controller
{
   public function posts(Request $request)
   {
      $file_name = null;

      $validator = Validator::make($request->all(), [
         'file'      => 'sometimes|nullable|mimes:jpeg,png,jpg|max:10000',
         'title'     => 'required',
         'content'   => 'required'
      ]);

      if ($validator->fails()) {
         return response()->json(['status' => 400, 'message' => 'Validation error', 'errors' => $validator->errors()]);
      }

      if ($request->hasFile('file')) {
         $file = $request->file('file');
         $category_path = 'svelte/';
         $original_path = public_path('storage/' . $category_path);
         $thumbnail_path = public_path('storage/' . $category_path . '/thumbnails');

         //create directory if not exists
         if (!File::isDirectory($original_path) || !File::isDirectory($thumbnail_path)) {
            File::makeDirectory($original_path, 0777, true, true);
            File::makeDirectory($thumbnail_path, 0777, true, true);
         }

         $file_name = md5(rand() * time()) . '.' . $request->file->getClientOriginalExtension();

         //Original file
         Image::make($file->getPathname())->save($original_path . '/' . $file_name);

         //Resize Original file
         Image::make($file->getPathname())->resize(400, 300)->save($thumbnail_path . '/' . $file_name);
      }

      $image_name = $file_name;

      $aa            = new Svelte();
      $aa->image     = $image_name;
      $aa->title     = request('title');
      $aa->content   = request('content');
      $aa->save();

      return ['status' => 200, 'message' => 'Success'];
   }

   public function get_posts()
   {
      $data = Svelte::get();
      if ($data) {
         return ['status' => 200, 'result' => $data];
      }

      return ['status' => 404, 'result' => 'No records found.'];
   }

   public function delete_posts($id)
   {
      $delete = Svelte::where('id', $id)->delete();
      if ($delete) {
         return ['status' => 200, 'message' => 'Delete success'];
      }

      return ['status' => 404, 'message' => 'Delete failed.'];
   }

   public function get_posts_by_id($id)
   {
      $data = Svelte::where('id', $id)->get();
      if ($data) {
         return ['status' => 200, 'result' => $data];
      }

      return ['status' => 404, 'message' => 'No records found.'];
   }

   public function update_posts(Request $request, $id)
   {
      $file_name = null;

      $validator = Validator::make($request->all(), [
         'file'      => 'sometimes|nullable|mimes:jpeg,png,jpg|max:10000',
         'title'     => 'required',
         'content'   => 'required'
      ]);

      if ($validator->fails()) {
         return response()->json(['status' => 400, 'message' => 'Validation error', 'errors' => $validator->errors()]);
      }

      $svelte = Svelte::find($id);

      if (!$svelte) {
         return response()->json(['status' => 404, 'message' => 'Post not found']);
      }

      if ($request->hasFile('file')) {
         $file = $request->file('file');
         $category_path = 'svelte/';
         $original_path = public_path('storage/' . $category_path);
         $thumbnail_path = public_path('storage/' . $category_path . '/thumbnails');

         //create directory if not exists
         if (!File::isDirectory($original_path) || !File::isDirectory($thumbnail_path)) {
            File::makeDirectory($original_path, 0777, true, true);
            File::makeDirectory($thumbnail_path, 0777, true, true);
         }

         $file_name = md5(rand() * time()) . '.' . $request->file->getClientOriginalExtension();

         //Original file
         Image::make($file->getPathname())->save($original_path . '/' . $file_name);

         //Resize Original file
         Image::make($file->getPathname())->resize(400, 300)->save($thumbnail_path . '/' . $file_name);
      }

      $image_name = $file_name;

      $svelte->image     = $image_name;
      $svelte->title     = $request->input('title');
      $svelte->content   = $request->input('content');
      $svelte->save();

      return response()->json(['status' => 200, 'message' => 'Update success']);
   }
}
