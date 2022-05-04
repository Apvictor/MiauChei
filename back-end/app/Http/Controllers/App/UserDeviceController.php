<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserDeviceRepository;

class UserDeviceController extends Controller
{
    public $userDeviceRepo;

    public function __construct(UserDeviceRepository $userDeviceRepo)
    {
        $this->userDeviceRepo = $userDeviceRepo;
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function registerDevice(Request $request)
    {
        $this->userDeviceRepo->updateOrCreate($request->all());

        return $this->sendSuccess('O dispositivo foi registrado com sucesso.');
    }

    /**
     * @param $playerId
     *
     * @return JsonResponse
     */
    public function updateNotificationStatus($playerId)
    {
        $this->userDeviceRepo->updateStatus($playerId);

        return $this->sendSuccess('O status da notificação foi atualizado com sucesso.');
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    private function sendSuccess($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
