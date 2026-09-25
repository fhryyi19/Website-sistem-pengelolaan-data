<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class QrAccessController extends Controller
{
    public function index(Request $request): View
    {
        // Mendapatkan IP Address lokal server
        $host = $request->getHost();
        $localIp = gethostbyname(gethostname());
        
        if (in_array($host, ['localhost', '127.0.0.1'])) {
            // Jika diakses via localhost, utamakan IP LAN yang terdeteksi
            $serverIp = ($localIp && $localIp !== '127.0.0.1') ? $localIp : $host;
        } else {
            $serverIp = $host;
        }

        $defaultPort = '8081';

        return view('qr-access.index', compact('serverIp', 'defaultPort'));
    }
}
