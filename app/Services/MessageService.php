<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;

class MessageService
{

    const GROUP_YEAR = 'year';
    const GROUP_MONTH = 'month';
    const GROUP_DAY = 'day';
    const FIRST_TIME = '00:00:00';
    const LAST_TIME = '23:59:59';

    public function total($period_start,  $period_end,$period_group_unit) {
        $result = [];
        switch ($period_group_unit) {
            case self::GROUP_YEAR:
                $result = $this->getMessageByYear($period_start,$period_end);
                break;
            case self::GROUP_MONTH:
                $result = $this->getMessageByMonth($period_start,$period_end);
                break;
            case self::GROUP_DAY:
                $result = $this->getMessageByDay($period_start,$period_end);
                break;
        }

        return $result;
    }

    /**
     * @param $period_start
     * @param $period_end
     * @return array
     * @throws \Exception
     */
    private function getMessageByYear($period_start,$period_end) {
        try {
            $firstStart = $period_start;
            $period_start = new \DateTime($period_start);
            $period_end = new \DateTime($period_end);
            $numberYear = (int)$period_end->format('y') - (int)$period_start->format('y');
            $periodYearData = [];

            for ($i = 1; $i <= $numberYear; $i++) {
                $start = $firstStart;
                $end = date('Y-m-d', strtotime('+1 years', strtotime($start)));

                if($i < $numberYear) {
                    $dataEnd = date('Y-m-d', strtotime('-1 day', strtotime($end)));
                } else {
                    $dataEnd = $period_end->format('Y-m-d');
                }
                $startDateTime = $start . ' ' . self::FIRST_TIME;
                $endDateTime = $dataEnd . ' ' . self::LAST_TIME;
                $itemCondition = $this->getConditionData($startDateTime, $endDateTime);

                $firstStart = $end;
                array_push($periodYearData, $itemCondition);
            }
            return $periodYearData;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $period_start
     * @param $period_end
     * @return array
     * @throws \Exception
     */
    private function getMessageByMonth($period_start,$period_end) {
        try {
            $nextStart = $period_start;
            $passedPeriodEnd = $period_end;
            $period_start = new \DateTime($period_start);
            $period_end = new \DateTime($period_end);
            $interval  = $period_start->diff($period_end);

            $yearsInMonths = $interval->format('%r%y') * 12;
            $months = $interval->format('%r%m');
            $totalMonths = $yearsInMonths + $months + 1;

            $periodMonthData = [];

            for ($i = 1; $i <= $totalMonths; $i++) {
                $start = $nextStart;
                $end = date('Y-m-d', strtotime('+1 month', strtotime($start)));
                $dataEnd = date('Y-m-d', strtotime('-1 day', strtotime($end)));

                if (strtotime($dataEnd) >= strtotime($passedPeriodEnd)) {
                    $dataEnd = $passedPeriodEnd;
                }
                $startDateTime = $start . ' ' . self::FIRST_TIME;
                $endDateTime = $dataEnd . ' ' . self::LAST_TIME;
                $itemCondition = $this->getConditionData($startDateTime, $endDateTime);
                array_push($periodMonthData, $itemCondition);
                $nextStart = $end;
            }

            return $periodMonthData;
        }  catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

    /**
     * @param $period_start
     * @param $period_end
     * @return array
     * @throws \Exception
     */
    private function getMessageByDay($period_start,$period_end) {
        try {
            $nextStart = $period_start;
            $datediff = strtotime($period_end) - strtotime($period_start);
            $totalDays =  (int)round($datediff / (60 * 60 * 24)) + 1;
            $periodDayData =  [];
            for ($i = 1; $i <= $totalDays; $i++) {
                $start = $nextStart;
                $end = $start;

                $startDateTime = $start . ' ' . self::FIRST_TIME;
                $endDateTime = $end . ' ' . self::LAST_TIME;
                $itemCondition = $this->getConditionData($startDateTime, $endDateTime);
                array_push($periodDayData, $itemCondition);
                $nextStart = date('Y-m-d', strtotime('+1 day', strtotime($end)));
                if (strtotime($nextStart) > strtotime($period_end)) break;
            }
            return $periodDayData;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

    }

    /**
     * @param $startDateTime
     * @param $endDateTime
     * @return array
     */
    private function getConditionData($startDateTime, $endDateTime) {
        $messageNumber = \App\Models\Message::whereBetween('received_date', [$startDateTime, $endDateTime])->count();
        return [
            'period_start' => $startDateTime,
            'period_end' => $endDateTime,
            'message_number' => $messageNumber
        ];
    }

    public function userActivity($period_start,$period_end,$limit, $dir) {
        $start = $period_start . ' ' . self::FIRST_TIME;
        $end = $period_end . ' ' . self::LAST_TIME;
        $messageData = \App\Models\Message::select('sender_id', \DB::raw('count(*) as message'))->whereBetween('received_date', [$start, $end])
            ->groupBy('sender_id')->orderBy('received_date',$dir)->limit($limit)->get();
        return $messageData;

    }
}
