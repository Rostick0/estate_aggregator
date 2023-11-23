<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Chat\IndexChatRequest;
use App\Http\Requests\Chat\StoreChatRequest;
use App\Models\Chat;
use App\Models\Recruitment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index(IndexChatRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Chat)
        );
    }

    public function store(StoreChatRequest $request)
    {
        if ($request->type === 'Recruitment') {
            Recruitment::findOrFail($request->type_id);
        }

        $data = Chat::firstOrCreate([
            'chatsable_type' => "App\Models\{$request->type}",
            'chatsable_id' => $request->type_id,
        ]);

        return new JsonResponse([
            'data' => $data
        ]);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new Chat, $id)
        );
    }
}
