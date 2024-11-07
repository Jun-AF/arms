<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $condition = ""; // properti kondisi
    protected $notif = ""; // properti notifikasi

    /**
     * Mengambil aktivitas2 user
     */
    public function activity()
    {
        $acts = Activity::where("actor_id", Auth::id())->where("is_read", false)->limit(5)->orderBy("id", "DEsC")->get();
        $count = Activity::where("actor_id", Auth::id())->where("is_read", false)->count();
        $activities = [$acts, $count];
        
        return $activities;
    }

    /**
     * Menyimpan aktivitas2 user
     */
    public function storeActivity($type, $activities)
    {
        $token = "";
        switch ($type) {
            case "Store":
                $token = "ST-" . random_int(10000, 999999);
                break;
            case "Update":
                $token = "UP-" . random_int(10000, 999999);
                break;
            case "Delete":
                $token = "DT-" . random_int(10000, 999999);
                break;
        } // Pemberian nilai token

        Activity::create([
            "actor_id" => Auth::id(),
            "token" => $token,
            "message" => $activities,
            "type" => $type,
            "created_at" => now(),
            "updated_at" => now()
        ]); // Simpan aktivitas
    }

    /**
     * Check apakah user role admin
     */
    public function is_admin() {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "You can't enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
    }

    /**
     * Memberikan toast condition dan notification
     */
    public function toastNotification($cond, $notif)
    {
        $this->condition = $cond;
        $this->notif = $notif;
    }

    public function getCondition()
    {
        return $this->condition; // Mengembalikan nilai condition
    }

    public function getNotif()
    {
        return $this->notif; // Mengembalikan nilai notif
    }
}
