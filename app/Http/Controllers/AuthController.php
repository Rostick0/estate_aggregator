<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\Auth\LoginAuthRequest;
use App\Http\Requests\Auth\RegisterAuthRequest;
use App\Models\User;
use App\Policies\AuthPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Login
     * @OA\Post (
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"john@test.com",
     *                     "password":"johnjohn1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="access_token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                  @OA\Property(property="token_type", type="string", example="Bearer"),
     *                  @OA\Property(property="expires_in", type="number", example=3600),
     *                  @OA\Property(property="user", type="object",
     *                      ref="#/components/schemas/UserSchema"
     *                  ),
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
     *                      @OA\Property(property="password", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The password field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function login(LoginAuthRequest $request)
    {
        $validated = $request->validated();

        if (!$token = JWTAuth::attempt($validated)) return new JsonResponse(['message' => 'Incorrect login or password'], 401);

        if (AuthPolicy::login(auth()->user())) return new JsonResponse(['message' => 'The account is not confirmed'], 401);

        return $this::createNewToken($token);
    }
    /**
     * Register
     * @OA\Post (
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      required={"name", "password", "phone", "role", "type_social"},
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="role",
     *                          type="enum: client,realtor,agency,builder",
     *                      ),
     *                      @OA\Property(
     *                          property="type_social",
     *                          type="enum: whatsapp,viber,telegram"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"John",
     *                     "email":"john@test.com",
     *                     "password":"johnjohn1",
     *                     "phone":"88005553535",
     *                     "role":"client",
     *                     "type_social":"whatsapp"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="access_token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                  @OA\Property(property="token_type", type="string", example="Bearer"),
     *                  @OA\Property(property="expires_in", type="number", example=3600),
     *                  @OA\Property(property="user", type="object",
     *                       ref="#/components/schemas/UserSchema"
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The name field is required. (and 2 more errors)"),
     *                  @OA\Property(property="errors", type="object",
     *                      @OA\Property(property="name", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The name field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email field is required.",
     *                          )
     *                      ),
     *                      @OA\Property(property="password", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The password field is required.",
     *                          )
     *                      ),
     *                  ),
     *          )
     *      )
     * )
     */
    public function register(RegisterAuthRequest $request)
    {
        $only = $request->validated();
        $password = $request->password;
        $is_confirm = false;

        if ($request->role === 'client') $is_confirm = true;

        $user = User::create([
            ...$only,
            'password' => Hash::make($password),
            'is_confirm' => $is_confirm
        ]);

        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $password
        ]);

        return $this::createNewToken($token);
    }

    /**
     * Logout
     * @OA\Post (
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User successfully signed out"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Token not founded"),
     *          )
     *      )
     * )
     */
    public function logout()
    {
        auth()?->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh
     * @OA\Post (
     *     path="/api/auth/refresh",
     *     tags={"Auth"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="access_token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                  @OA\Property(property="token_type", type="string", example="Bearer"),
     *                  @OA\Property(property="expires_in", type="number", example=3600),
     *                  @OA\Property(property="user", type="object",
     *                       ref="#/components/schemas/UserSchema"
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Token not founded"),
     *          )
     *      )
     * )
     */
    public function refresh()
    {
        return $this::createNewToken(auth()?->refresh());
    }

    /**
     * Me
     * @OA\Get (
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="extends",
     *          description="Extends data",
     *          in="query",
     *          example="contacts,country,image,flat_owners,alert",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),    
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/UserSchema"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Token not founded"),
     *          )
     *      )
     * )
     */
    public function me(Request $request)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new User, auth()->id())
        ]);
    }

    public static function createNewToken($token)
    {
        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()?->factory()->getTTL() * 60 * 24 * 7,
                'user' => auth()->user()
            ]
        ], 201);
    }
}
