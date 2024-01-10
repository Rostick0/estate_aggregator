<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Ticket\DestroyTicketRequest;
use App\Http\Requests\Ticket\IndexTicketRequest;
use App\Http\Requests\Ticket\ShowTicketRequest;
use App\Models\Ticket;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    /**
     * Index
     * @OA\Get (
     *     path="/api/ticket",
     *     tags={"Ticket"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          @OA\Schema(
     *              type="object",
     *              example={
     *                 "filter[id]":null,
     *                 "filter[email]":null,
     *                 "filter[phone]":null,
     *                 "filter[full_name]":null,
     *                 "filter[text]":null,
     *                 "filter[communiction_method]":null,
     *                 "filter[purpose]":null,
     *                 "filter[link_from]":null,
     *                 "filter[ticket_type_cid]":null,
     *                 "filter[status_cid]":null,
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
     *          example="flat,status",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/TicketSchema"
     *              ),
     *          )
     *      ),
     * )
     */
    public function index(IndexTicketRequest $request)
    {
        return new JsonResponse(
            Filter::all($request, new Ticket)
        );
    }

    /**
     * Store
     * @OA\Post (
     *     path="/api/ticket",
     *     tags={"Ticket"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"phone", "full_name", "text", "communiction_method", "link_from", "ticket_type_cid"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),                   
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="full_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="communiction_method",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="purpose",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="link_from",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="ticket_type_cid",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *                     "email":"john@test.com",
     *                     "phone":"+799999",
     *                     "full_name":"Олег",
     *                     "text": "Мне понравилась одна квартира, хотел бы ...",
     *                     "communiction_method": "telegram",
     *                     "purpose": null,
     *                     "link_from": "http://92.63.179.235:3002/",
     *                     "ticket_type_cid": 8,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/TicketSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The email field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="phone", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The phone field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function store(StoreTicketRequest $request)
    {
        $data = Ticket::create($request->validated());

        return new JsonResponse([
            'data' => $data
        ], 201);
    }

    /**
     * Show
     * @OA\Get (
     *     path="/api/ticket/{id}",
     *     tags={"Ticket"},
     *      @OA\Parameter( 
     *          name="id",
     *          in="path",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="status",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/TicketSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Not found"),
     *                  ),
     *          )
     *      )
     * )
     */
    public function show(ShowTicketRequest $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new Ticket, $id)
        ]);
    }

    /**
     * Update
     * @OA\Put (
     *     path="/api/ticket/{id}",
     *     tags={"Ticket"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"phone", "full_name", "text", "communiction_method", "link_from", "ticket_type_cid", "status_cid"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),                   
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="full_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="text",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="communiction_method",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="purpose",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="link_from",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="ticket_type_cid",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="status_cid",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *                     "email":"john@test.com",
     *                     "phone":"+799999",
     *                     "full_name":"Олег",
     *                     "text": "Мне понравилась одна квартира, хотел бы ...",
     *                     "communiction_method": "telegram",
     *                     "purpose": null,
     *                     "link_from": "http://92.63.179.235:3002/",
     *                     "ticket_type_cid": 8,
     *                     "status_cid": 4,
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/TicketSchema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The email field is required. (and 1 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="phone", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The phone field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function update(UpdateTicketRequest $request, int $id)
    {
        $data = Ticket::findOrFail($id);
        $data->update(
            $request->validated()
        );

        return new JsonResponse([
            'data' => Ticket::find($id)
        ]);
    }

    /**
     * Delete
     * @OA\Delete (
     *     path="/api/ticket/{id}",
     *     tags={"Ticket"},
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
    public function destroy(DestroyTicketRequest $request, int $id)
    {
        $deleted = Ticket::destroy($id);

        if (!$deleted) return new JsonResponse([
            'message' => 'Not deleted',
            404
        ]);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
