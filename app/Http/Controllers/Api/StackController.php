<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetStacks;
use App\Services\GetFile;
use App\Services\UploadFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Stack;

class StackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetStacks $getStacks)
    {
        return response()->json($getStacks->execute());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Services\UploadFile $uploadFile
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadFile $uploadFile, Request $request)
    {
        $data = $request->validate([
            'index' => 'required|integer',
            'image' => 'required'
        ]);

        $file = $uploadFile->execute($data['image']);

        $index = Stack::max('index');
        if ($data['index'] > $index + 1)
            $data['index'] = $index + 1;


        $order = Stack::where('index', $data['index'])->max('order');
        $stack = Stack::create([
            'index' => $data['index'],
            'order' => $order + 1,
            'name' => $file['originalName'],
            'path' => $file['path']
        ]);

        return response()->json($stack);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Services\GetFile $getFile
     * @param \App\Services\UploadFile $uploadFile
     * @return \Illuminate\Http\Response
     */
    public function generate(GetFile $getFile, UploadFile $uploadFile)
    {
        $data = [];
        $directories = Storage::directories('images');
        foreach($directories as $index => $directory) {

            $files = Storage::files($directory);
            foreach($files as $order => $file) {

                $image = $getFile->execute(storage_path("app/{$file}"));
                $file = $uploadFile->execute($image);

                $now = now();
                $data[] = [
                    'index' => $index,
                    'order' => $order,
                    'name' => $file['originalName'],
                    'path' => $file['path'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }
        
        Stack::truncate();
        Stack::insert($data);
    }
}
