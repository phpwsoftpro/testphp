<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTotalRequest;
use App\Services\MessageService;
use Illuminate\Http\Request;

class Message extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
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
}
