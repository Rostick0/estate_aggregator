<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $request_only = [
        'name',
        'email',
        'phone',
        'role',
        'avatar',
        'country_id',
        'type_social',
    ];

    private static function extendsMutation($data, $request)
    {
    }

    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new User)
        );
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new User, $id)
        ]);
    }

    public function update(UserUpdateRequest $request, int $id)
    {
        $data = User::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only($this->request_only)
        );

        $this::extendsMutation($data, $request);

        return new JsonResponse([
            'data' => Filter::one($request, new User, $id)
        ]);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if (AccessUtil::cannot('delete', $user)) return AccessUtil::errorMessage();

        User::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
