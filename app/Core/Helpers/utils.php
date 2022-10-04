<?php

use App\Models\Admin;
use App\Models\Request;

function generateTicketID($start = 0, $length = 12) {
	$code = mt_rand(10000, 999999999999);
	$currentTime = time();
	return substr('DP' . str_shuffle($currentTime . $code), $start, $length);
}

function tinyUrl($url) {
	// $tiny = 'http://tinyurl.com/api-create.php?url='.$url;
	// return file_get_contents($tiny);
	return $url;
}

function tinyUrlTesting($url) {
	$tiny = 'http://tinyurl.com/api-create.php?url=';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$tiny.urlencode(trim($url)) );
	$response = curl_exec($ch);
	curl_close($ch);
	/* $response = \Http::post($tiny,[
		'url' => $url
	]);
	return $response; */
	// return $response;
}
function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }
function prepare_text($text, $data = []) {

	return str_replace(array_keys($data), array_values($data), $text);
}

function randomSupportAdmin($identifier = 'supports.internal.actions', $exclude = []) {
	return Admin::query()->where('status', 1)->inRandomOrder()->whereRaw('role IN
        (
        SELECT
            role_id
        FROM
            role_permissions
        WHERE
        permission_id IN
        (
        SELECT
            id
        FROM
            permissions_list
        WHERE
            identifier = ?
        ) AND
        value = ?
    )', ['identifier' => $identifier, 'value' => 1])
		->where('id', '!=', auth('admin')->id())
		->when(count($exclude) > 0, function ($q) use ($exclude) {
			$q->whereNotIn('id', $exclude);
		})
		->first();
}

function frontend_url($string) {
	return config('app.frontend_url') . '/' . $string;
}

function pendingRequestsCount($userId, $requestId = null) {
	return Request::whereIn('status', ['pending', 'assigned'])
		->when(!empty($requestId), function ($q) use ($requestId) {
			$q->where('id', '!=', $requestId);
		})
		->where(function ($q) use ($userId) {
			$q->where('requested_by', $userId)
				->orWhere('admin_id', $userId)
				->orWhere('passed_to', $userId);
		})->count();
}

function getWalletBalance($userId = null) {
	if (!$userId) {
		$userId = auth()->id();
	}

	$amount = DB::select('SELECT IFNULL((SELECT SUM(amount) FROM wallet_logs WHERE user_id = ? AND type = ?),0) as out_amount, IFNULL((SELECT SUM(amount) FROM wallet_logs WHERE user_id = ? AND type = ?),0) as in_amount', [$userId, 'out', $userId, 'in']);
	$amount = end($amount);
	return abs($amount->in_amount - $amount->out_amount);
}
