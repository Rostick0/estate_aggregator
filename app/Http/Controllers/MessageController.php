<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Message\IndexMessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Utils\AccessUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    private static function extendsMutation($data, $request)
    {
        $data->images()->delete();
        if ($request->images) {
            $images = array_map(function ($image_id) {
                return ['image_id' => $image_id];
            }, QueryString::convertToArray($request->images));

            $data->images()->createMany($images);
        }

        $data->files()->delete();
        if ($request->files) {
            $files = array_map(function ($file_id) {
                return ['file_id' => $file_id];
            }, QueryString::convertToArray($request->files));

            $data->files()->createMany($files);
        }
    }

    public function index(IndexMessageRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Message)
        );
    }


    public function store(StoreMessageRequest $request)
    {
        $data = Message::create([
            'user_id' => auth()->id()
        ]);

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => $data
        ]);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new Message, $id)
        );
    }

    public function update(UpdateMessageRequest $request, int $id)
    {
        $data = Message::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only(['content'])
        );

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => Filter::one($request, new Message, $id)
        ]);
    }


    public function destroy(int $id)
    {
        $message = Message::findOrFail($id);

        if (AccessUtil::cannot('delete', $message)) return AccessUtil::errorMessage();

        Message::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
