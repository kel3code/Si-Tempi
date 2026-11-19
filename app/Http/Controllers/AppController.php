<?php
namespace App\Http\Controllers;
use App\Services\StateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {
    function index() {
        return view('app', ['boot' => ['user' => Auth::user()?->name, 'state' => Auth::check() ? StateService::export() : null]]);
    }
    function sync(Request $r) {
        $r->validate(['orders' => 'array', 'riwayat' => 'array', 'penggunaan' => 'array', 'now' => 'numeric', 'min' => 'numeric']);
        StateService::import($r->all()); return ['ok' => true];
    }
}
