<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Asset;
use App\Models\History;
use App\Models\Office;
use App\Models\Person;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware("auth")->except("welcome");
    }

    public function welcome()
    {
        if (Auth::check()) {
            return redirect("dashboard");
        }

        return view("welcome");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Year definition
        $year = 0000;
        if ($request->year == 0) {
            $year = date("Y");
        } else {
            $year = $request->year;
        }
        
        $activities = $this->activity();

        $assets_count = Asset::all()->count();
        $offices_count = Office::all()->count();
        $users_count = Person::Where('name','<>','STANDBY')->count();

        /**
         * Dari menarik data asset dan history didapati bentuk dari data tersebut [date, count]
         */
        $assets_pool = $this->asset_count(
            DB::table("assets")
                ->select(
                    DB::raw("assets.asset_in, COUNT(assets.asset_in) AS count")
                )
                ->groupBy("assets.asset_in")
                ->where("assets.asset_in", "LIKE", "" . $year . "%")
                ->get()
        );
        $histories_pool = $this->history_count(
            DB::table("histories")
                ->select(
                    DB::raw("histories.transaction_date, COUNT(histories.transaction_date) AS count")
                )
                ->groupBy("histories.transaction_date")
                ->where("histories.transaction_date", "LIKE", "" . $year . "%")
                ->get()
        );
        $condition = $this->condition;
        $notif = $this->notif;

        return view(
            "home",
            compact(
                "assets_count",
                "offices_count",
                "users_count",
                "assets_pool",
                "histories_pool",
                "year",
                "activities",
                "condition",
                "notif"
            )
        );
    }

    public function setting() {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        return view('setting');
    }

    /**
     * Deleting all table
     *
     */
    public function truncateAll(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $validator = $request->validate([
            "password" => ["required", "string"],
        ]);

        if (!Hash::make($request->password) == Auth::user()->password) {
            return redirect("settings");
        }

        try {
            DB::statement("SET FOREIGN_KEY_CHECKS=0");
            Office::truncate();
            Asset::truncate();
            Person::truncate();
            History::truncate();
            Validation::truncate();
            Activity::truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS=1");
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in truncating all tables");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted all asset records"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity =
            "Danger!, all tables have just truncated. 5 tables are truncated";
        $this->storeActivity("Delete", $activity);

        return redirect("settings")
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    /**
     * Deleting all table
     *
     */
    public function truncateActivity(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $validator = $request->validate([
            "password" => ["required", "string"],
        ]);

        if (!Hash::make($request->password) == Auth::user()->password) {
            return redirect("settings");
        }

        try {
            DB::statement("SET FOREIGN_KEY_CHECKS=0");
            Activity::truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS=1");
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in truncating all tables");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted all asset records"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity =
            "Danger!, all tables have just truncated. 5 tables are truncated";
        $this->storeActivity("Delete", $activity);

        return redirect("settings")
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    protected function asset_count($records)
    {
        $ndate = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($records as $rec) {
            for ($i = 0; $i <= sizeof($ndate); $i++) {
                if ($i == intval(substr($rec->asset_in, 5, 2)) -1 ) {
                    $ndate[$i] = intval($rec->count);
                }
            }
        }
        return $ndate;
    }

    protected function history_count($records)
    {
        $ndate = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($records as $rec) {
            for ($i = 0; $i <= sizeof($ndate); $i++) {
                if ($i == intval(substr($rec->transaction_date, 5, 2)) - 1) {
                    $ndate[$i] = intval($rec->count);
                }
            }
        }
        return $ndate;
    }
}
