<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $active;
    private $api_id;

    public function __construct($active = false)
    {
        $this->active = $active;

        $optionsCollect = Cache::get('options', []);

        if ($active) {
            $this->api_id =  $optionsCollect->firstWhere('name_en', '=', 'api_id_active')['value'];
        } else {
            $this->api_id =  $optionsCollect->firstWhere('name_en', '=', 'api_id_deactive')['value'];
        }
    }

    public function checkPhone($phone)
    {
        if (!$this->active) {
            return (object) array('status' => 'OK', "status_code" => 100, "call_phone" =>  "79397524410", "call_phone_pretty" => "+7 (939) 752-4410", 'check_id' => 777);
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        $ch = curl_init("https://sms.ru/callcheck/add");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "api_id" => $this->api_id,
            "phone" => $phone,
            "json" => 1
        ));

        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body);

        if ($json) {
            if ($json->status == "OK") {
               return $json;
            } else {
                Log::info("Сообщение на номер $phone не отправлено. ");
                Log::info("Код ошибки: $json->status_code. ");
                Log::info("Текст ошибки: $json->status_text. ");
                return false;
            }
        } else {
            Log::info("Запрос не выполнился Не удалось установить связь с сервером");
            return false;
        }
    }

    public function checkId($id)
    {
        if (!$this->active) {
            return (object) array('status' => 'OK', "status_code" => 100, "call_phone" =>  "79397524410", "call_phone_pretty" => "+7 (939) 752-4410", 'check_id' => 777);
        }

        $ch = curl_init("https://sms.ru/callcheck/status");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "api_id" => $this->api_id,
            "check_id" => $id,
            "json" => 1
        ));

        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body);

        if ($json) {
            if ($json->status == "OK") {
               return $json;
            } else {
                Log::info("Сообщение на номер $id не отправлено. ");
                Log::info("Код ошибки: $json->status_code. ");
                Log::info("Текст ошибки: $json->status_text. ");
                return false;
            }
        } else {
            Log::info("Запрос не выполнился Не удалось установить связь с сервером");
            return false;
        }
    }
}
