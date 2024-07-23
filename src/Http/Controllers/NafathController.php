<?php

namespace MohamadZatar\Nafath\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MohamadZatar\Nafath\Models\NafathLogin;
use MohamadZatar\Nafath\Enums\NafathLoginStatusEnum;


class NafathController extends \Illuminate\Routing\Controller
{
    /**
     * Handle the login request to Saudi Nafath.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLoginRequest(Request $request)
    {
        $subdomain = config('nafath.url_base'); // Access package config
        $url = "https://$subdomain.semati.sa/nafath/api/v1/client/authorize/";
        $data = [
            'id' => $request->input('id_number'),
            'action' => 'SpRequest',
            'service' => 'Login',
            'callbackUrl' => config('nafath.NAFATH_CALLBACK_URL'),
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'apikey ' . config('nafath.api_key'),
            ])->post($url, $data);

            $statusCode = $response->status();
            $responseBody = $response->json();

            if ($statusCode !== 200) {
                Log::error('NAFATH Error', ['error' => $responseBody['error'] ?? 'Unknown error']);
                return response()->json(['error' => $responseBody['error'] ?? 'Unknown error'], $statusCode);
            }

            Log::info('NAFATH Success', $responseBody);

            NafathLogin::create([
                'trans_id' => $responseBody['transId'],
                'random' => $responseBody['random'],
                'status' => NafathLoginStatusEnum::PENDING
            ]);

            return response()->json([
                'trans_id' => $responseBody['transId'],
                'random' => $responseBody['random'],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Nafath EXCEPTION !!!', [
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile(),
            ]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function nafathPostCallback(Request $request)
    {
        try {
            $decodedToken = $this->decodeJwt($request->response);
            $responseCollection = collect($decodedToken);

            $trans_id = $responseCollection->get('transId');
            $status = $responseCollection->get('status');

            Log::channel('nafath')->debug('CallBack Parameters:', [
                'trans_id' => $trans_id,
                'status' => $status,
            ]);

            $login = NafathLogin::where('trans_id', $trans_id)->first();
            if (!$login) {
                Log::channel('nafath')->warning('Nafath Callback: No login found for trans_id', [
                    'trans_id' => $trans_id
                ]);
                return response('No login found', 404);
            }
            if ($status === NafathLoginStatusEnum::COMPLETED) {
                event(new VerificationVerified($login->user_id));
            } else {
                event(new VerificationRejected($login->user_id));
            }

        } catch (Exception $e) {
            Log::channel('nafath')->error('Nafath EXCEPTION!!!:', [
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile(),
            ]);
        }

        return response('TRS', 200);
    }

    private function decodeJwt($token)
    {
        $base64UrlEncodedPayload = explode('.', $token)[1];
        $base64UrlDecodedPayload = strtr($base64UrlEncodedPayload, '-_', '+/');
        return json_decode(base64_decode($base64UrlDecodedPayload));
    }
}
