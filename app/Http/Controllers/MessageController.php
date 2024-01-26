<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Message\IndexMessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Events\Message as EventsMessage;
use App\Utils\AccessUtil;
use App\Utils\QueryString;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()?->user()?->role !== 'admin') {
            $where[] = ['user_id', '=', auth()?->id(), 'chat.chat_users'];
        }

        return $where;
    }

    private static function extendsMutation($data, $request)
    {
        $data->images()->delete();
        if ($request->has('images')) {
            $images = array_map(function ($image_id) {
                return ['image_id' => $image_id];
            }, QueryString::convertToArray($request->images));

            $data->images()->createMany($images);
        }

        $data->files()->delete();
        if ($request->has('files')) {
            $files = array_map(function ($file_id) {
                return ['file_id' => $file_id];
            }, QueryString::convertToArray($request->files));

            $data->files()->createMany($files);
        }
    }

    /**
     * Index
     * @OA\get (
     *     path="/api/message",
     *     tags={"Message"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[content]":null,
     *                 "filter[is_read]":null,
     *                 "filter[chat_id]":null,
     *                 "filter[user_id]":null,
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
     *          example="chat,user,images,files",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MessageSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexMessageRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Message, [], $this::getWhere())
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/message",
     *     tags={"Message"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                      @OA\Property(
     *                          property="content",
     *                          type="string",
     *                          example="Я хочу написать",
     *                      ),
     *                      @OA\Property(
     *                          property="images",
     *                          type="string",
     *                          example="1,2"
     *                      ),
     *                      @OA\Property(
     *                          property="files",
     *                          type="number",
     *                          example=""
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MessageSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The content field is required when none of images, files are present"),
     *          )
     *      )
     * )
     */
    public function store(StoreMessageRequest $request)
    {
        $data = Message::create([
            ...$request->only(['content', 'chat_id']),
            'user_id' => auth()->id()
        ]);

        $this::extendsMutation($data, $request);

        EventsMessage::dispatch([
            'data' => Message::with(['images.image'])->find($data->id),
            'type' => 'create'
        ]);

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\get (
     *     path="/api/message/{id}",
     *     tags={"Message"},
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
     *          example="chat,user,images,files",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MessageSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function show(Request $request, int $id)
    {
        return new JsonResponse(
            Filter::one($request, new Message, $id, $this::getWhere())
        );
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/message/{id}",
     *     tags={"Message"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="User id",
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
     *                      @OA\Property(
     *                          property="content",
     *                          type="string",
     *                          example="Я хочу написать",
     *                      ),
     *                      @OA\Property(
     *                          property="images",
     *                          type="string",
     *                          example="1,2"
     *                      ),
     *                      @OA\Property(
     *                          property="files",
     *                          type="number",
     *                          example=""
     *                      ),
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/MessageSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The content field is required when none of images, files are present"),
     *          )
     *      )
     * )
     */
    public function update(UpdateMessageRequest $request, int $id)
    {
        $data = Message::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only(['content'])
        );

        $this::extendsMutation($data, $request);

        EventsMessage::dispatch([
            'data' => Message::with(['images.image'])->find($id),
            'type' => 'update'
        ]);

        return new JsonResponse([
            'data' => Filter::one($request, new Message, $id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/message/{id}",
     *     tags={"Message"},
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
        $message = Message::findOrFail($id);

        if (AccessUtil::cannot('delete', $message)) return AccessUtil::errorMessage();

        Message::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }

    public function read(Request $request, int $id)
    {
        $message = Filter::one($request, new Message, $id, $this::getWhere());

        if (!$message) return AccessUtil::errorMessage();

        Message::where([
            ['id', '<=', $id],
            ['chat_id', '=', $message->chat_id],
            ['user_id', '!=', auth()->id()],
        ])->update([
            'is_read' => 1
        ]);

        return new JsonResponse([
            'message' => 'read',
        ]);
    }
}
