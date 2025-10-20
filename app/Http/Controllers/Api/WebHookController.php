<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function store(Request $request)
    {
        $optionsCollect = Cache::get('options', []);

        $api_id = $optionsCollect->firstWhere('name_en', '=', 'api_id_active')['value'];

        $hash = "";
        foreach ($_POST["data"] as $entry) {
            $hash .= $entry;
        }
        if ($_POST["hash"] == hash("sha256", $api_id . $hash)) {
            foreach ($_POST["data"] as $entry) {
                $lines = explode("\n", $entry);
                switch ($lines[0]) {
                    case "callcheck_status":
                        $check_id = $lines[1];
                        $check_status = $lines[2];

                        $user = User::where('check_id', $check_id)->first();

                        if ($user) {
                            if ($check_status == "401") {
                                $user->phone_verified_at = now();
                                $user->phone_fore_verification = null;
                                $user->check_id = null;

                                $user->save();
                            } elseif ($check_status == "402") {
                                $user->delete();
                            }
                        }
                        break;
                }
            }
        }

        return 100;
    }
}
