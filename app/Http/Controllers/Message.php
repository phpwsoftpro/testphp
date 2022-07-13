<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageTotalRequest;
use App\Http\Requests\UserActivityRequest;
use App\Services\MessageService;

class Message extends Controller
{

    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @param MessageTotalRequest $request
     * @return array
     */
    public function total(MessageTotalRequest  $request) {
        $period_start = $request->get('period_start');
        $period_end = $request->get('period_end');
        $period_group_unit = $request->get('period_group_unit');
        $result = $this->messageService->total($period_start, $period_end, $period_group_unit);
        return json_encode($result);
    }

    public function getDataForChart(MessageTotalRequest  $request) {
        $period_start = $request->get('period_start');
        $period_end = $request->get('period_end');
        $period_group_unit = $request->get('period_group_unit');
        $result = $this->messageService->total($period_start, $period_end, $period_group_unit);
        $responseData = [
            'labels' => [],
            'data' => []
        ];
        if ($result) {
            foreach ($result as $item) {
                $responseData['labels'][] = date('Y-m-d', strtotime( $item['period_start'])) .'-'. date('Y-m-d', strtotime( $item['period_end']));
                $responseData['data'][] = $item['message_number'];
            }
        }

        return response()->json($responseData);
    }

    public function userActivity (UserActivityRequest $request) {
        $period_start = $request->get('period_start');
        $period_end = $request->get('period_end');
        $limit = $request->get('limit');
        $dir  = $request->get('dir');
        $result= $this->messageService->userActivity($period_start,$period_end, $limit, $dir);
        return response()->json($result);
    }
}
