<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Chat\IndexChatRequest;
use App\Http\Requests\Chat\StoreChatRequest;
use App\Models\Chat;
use App\Models\Flat;
use App\Models\Recruitment;
use App\Utils\AccessUtil;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ChatController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['user_id', '=', auth()?->id(), 'chat_users'];
        }

        return $where;
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/chat",
     *     tags={"Chat"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[chatsable_type]":null,
     *                 "filter[chatsable_id]":null,
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
     *          example="chat_users,last_message",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexChatRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Chat, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/chat",
     *     tags={"Chat"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"chatsable_type", "chatsable_id"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                     @OA\Property(
     *                          property="chatsable_type",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="chatsable_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *                     "chatsable_type": "Recruitment",
     *                     "chatsable_id": 1,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The chatsable_type field is required"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreChatRequest $request)
    {
        $user_id = null;

        if ($request->type === 'Recruitment') {
            $user_id =  Recruitment::findOrFail($request->type_id)?->user_id;
        } else if ($request->type === 'Flat') {
            $user_id = Flat::findOrFail($request->type_id)?->user_id;
        }

        if ($user_id == auth()->id()) {
            return AccessUtil::errorMessage('Forbidden', 400);
        }

        $data = Chat::where([
            'chatsable_type' => "App\\Models\\" . $request->type,
            'chatsable_id' => $request->type_id,
        ])->whereHas('chat_users', function (Builder $query) {
            $query->where('user_id', '=', auth()->id());
        })->first();

        if (!$data) {
            $data = Chat::create([
                'chatsable_type' => "App\\Models\\" . $request->type,
                'chatsable_id' => $request->type_id,
                'last_message_created_at' => Carbon::now()
            ]);

            $data->chat_users()->createMany([
                [
                    'user_id' => $user_id
                ],
                [
                    'user_id' => auth()->id()
                ]
            ]);
        }

        return new JsonResponse([
            'data' => $data
        ]);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/chat/{id}",
     *     tags={"Chat"},
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
     *          example="chat_users,last_message",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/ChatSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new Chat, $id, $this::getWhere())
        );
    }
}
