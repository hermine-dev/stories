<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Story;
use App\StoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StoriesController extends Controller
{
    public function index()
    {
        return Story::with('items')->get();
    }

    public function show($id)
    {
        return Story::where('id', '=', $id)->with('items')->first();
    }

    public function save(Request $request)
    {
        // this need for upload big file sizes
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '200M');

        $data = $request->validate([
            'page_name' => 'required',
            'id' => 'integer|nullable',
        ]);

        $story_id = isset($data['id']) ? $data['id'] : null;
        $story = new Story();
        if ($story_id) {
            $story = Story::find($story_id);
            if (empty($story)) {
                return response()->json(['status' => false, 'message' => 'Invalid story id'], 400);
            }
        }

        $story->page_name = $data['page_name'];
        if ($story->save()) {
            $storyItems = [];
            $nowTime = Carbon::now();
            foreach ($request->all() as $file) {
                if (is_file($file)) {
                    $randomName = uniqid() . Str::random(16);
                    $mime_type = $file->getMimeType();

                    $type = null;
                    if (substr($mime_type, 0, 5) == 'image') { // checking is file image
                        $type = 'image';
                    } else if (in_array($mime_type, [
                        'video/mp4', // todo only this format allowing for now
//                        'video/x-flv',
//                        'application/x-mpegURL',
//                        'video/MP2T',
//                        'video/3gpp',
//                        'video/quicktime',
//                        'video/x-msvideo',
//                        'video/x-ms-wmv'
                    ])) { // checking is file video
                        $type = 'video';
                    } else { // invalid file type, the file can be image or video
                        continue;
                    }

                    $ext = $file->guessExtension();
                    $original_name = $file->getClientOriginalName();
                    $file_name = $randomName . '.' . $ext;
                    $file->storeAs('public/uploads/' . $story->id, $file_name);


                    array_push($storyItems,
                        [
                            'path_name' => $file_name,
                            'original_name' => $original_name,
                            'story_id' => $story->id,
                            'type' => $type,
                            'mime_type' => $mime_type,
                            'created_at' => $nowTime,
                            'updated_at' => $nowTime,
                        ]
                    );
                }
            }
            if (!empty($storyItems)) {
                StoryItem::insert($storyItems);
            }
        }

        return response()->json(['status' => true, 'message' => 'The story saved.'], 200);
    }


    public function removeItem($id)
    {
        return StoryItem::destroy($id);
    }
}
