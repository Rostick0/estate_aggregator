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
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['user_id', '=', auth()?->id()];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/chat-user",
     *     tags={"ChatUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[chat_id]":null,
     *                 "filter[user_id]":null,
     *                 "filter[is_favorite]":null,
     *                 "filter[created_at]":null,
     *                 "filter[updated_at]":null,
     *               }
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="sort",
     *          description="Сортировка по параметру",
     *          in="query",
     *          example="id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Page",
     *          in="query",
     *          example="2",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="Limit data",
     *          in="query",
     *          example="20",
     *          @OA\Schema(
     *              type="number",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="chat,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexChatUserRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new ChatUser, [], $this::getWhere())
        );
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/chat-user/{id}",
     *     tags={"ChatUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          example="1",
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="chat,user",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatUserSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new ChatUser, $id, $this::getWhere())
        );
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/chat-user/{id}",
     *     tags={"ChatUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      required={"is_favorite"},
     *                      @OA\Property(
     *                          property="is_favorite",
     *                          type="boolean",
     *                          example="true",
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatUserSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The is_favorite field is required."),
     *          )
     *      )
     * )
     */
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

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/chat-user/{id}",
     *     tags={"ChatUser"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Deleted"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Access error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="No access"),
     *                 ),
     *          )
     *      )
     * )
     */
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
