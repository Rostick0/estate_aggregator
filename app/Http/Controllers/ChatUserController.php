<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\ChatUser\IndexChatUserRequest;
use App\Models\ChatUser;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatUserController extends Controller
{

    public function index(IndexChatUserRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new ChatUser)
        );
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new ChatUser, $id)
        );
    }

    public function update(Request $request, int $id)
    {
        $data = ChatUser::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only(['is_favorite'])
        );

        return new JsonResponse([
            'data' => Filter::one($request, new ChatUser, $id)
        ]);
    }

    public function destroy(int $id)
    {
        $chat_user = ChatUser::findOrFail($id);

        if (AccessUtil::cannot('delete', $chat_user)) return AccessUtil::errorMessage();

        ChatUser::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
