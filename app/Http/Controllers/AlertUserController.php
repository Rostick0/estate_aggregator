<?php

namespace App\Http\Controllers;

use App\Filters\Filter;
use App\Http\Requests\AlertUserRequest\StoreAlertUserRequest;
use App\Models\Alert;
use App\Models\AlertUser;
use App\Models\User;
use App\Utils\AccessUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertUserController extends Controller
{
    private static function getWhere()
    {
        $where = [];

        if (auth()->user()->role !== 'admin') {
            $where[] = ['user_id', '=', auth()->id()];
        }

        return $where;
    }


    public function index(Request $request)
    {
        return new JsonResponse(
            Filter::all($request, new AlertUser, [], $this::getWhere())
        );
    }


    public function store(StoreAlertUserRequest $request)
    {
        if ($request->user_id) {
            AlertUser::create([
                $request->only(['alert_id', 'user_id'])
            ]);
        } else {
            $alert = Alert::find($request->alert_id);
            $user = User::query();

            if ($alert->country_id) $user->where('country_id', $alert->country_id);
            if ($alert->role) $user->where('role', $alert->role);

            $user->alert()->create([
                'alert_id' => $request->alert_id
            ]);

            // AlertUser::create();
        }

        return new JsonResponse([
            'data' => [
                'message' => 'Created'
            ]
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        return new JsonResponse([
            'data' => Filter::one($request, new AlertUser, $id, $this::getWhere())
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = AlertUser::findOrFail($id);

        if (AccessUtil::cannot('update', $data)) return AccessUtil::errorMessage();

        $data->update(
            $request->only('is_read')
        );

        return new JsonResponse([
            'data' => Filter::one($request, new AlertUser, $id)
        ]);
    }


    public function destroy(int $id)
    {
        $partner = AlertUser::findOrFail($id);

        if (AccessUtil::cannot('delete', $partner)) return AccessUtil::errorMessage();

        AlertUser::destroy($id);

        return new JsonResponse([
            'message' => 'Deleted'
        ]);
    }
}
