<?php

namespace App\Http\Controllers;

use App\Exports\ValidationExport;
use App\Models\Office;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ValidationController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $activities = $this->activity();
        $offices = Office::all();

        return view(
            "assets.validations.index",
            compact("offices", "activities")
        );
    }

    public function list($office)
    {
        $activities = $this->activity();
        $office = Office::select("id","office_name")
            ->where("office_name", $office)
            ->get();
        $validations = DB::table("assets")
            ->select(
                "assets.id",
                "assets.asset_name",
                "assets.sn",
                "assets.office_id",
                "offices.office_name",
                "validations.condition",
                "validations.comment",
                "validations.month_period",
                "validations.id as validation_id",
                "validations.is_validate",
                "validations.validator_id"
            )
            ->leftJoin("offices", "offices.id", "assets.office_id")
            ->leftJoin("validations", "validations.asset_id", "assets.id")
            ->where("assets.office_id", $office[0]->id)
            ->orderBy("assets.id", "desc")
            ->get();

        $valids_count = sizeof(
            Validation::where("office_id", $office[0]->id)
                ->where("is_validate", true)
                ->get()
        );
        $total = sizeof($validations);

        return view(
            "assets.validations.list",
            compact("office", "validations", "valids_count", "total", "activities")
        );
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            "asset_id" => ["required", "integer", "unique:validations,asset_id"],
            "validator_id" => ["required", "integer"],
            "office_id" => ["required", "integer"],
            "condition" => ["required", "string"],
            "comment" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            Validation::create([
                "asset_id" => $request->asset_id,
                "validator_id" => $request->validator_id,
                "office_id" => $request->office_id,
                "condition" => $request->condition,
                "comment" => $request->comment,
                "month_period" => date("M-Y"),
                "is_validate" => true,
                "created_at" => now(),
                "updated_at" => now()
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just validated an asset"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message = "An asset has validated";
        $this->storeActivity("Store", $message);

        return redirect()
            ->back()
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            "condition" => ["required", "string"],
            "comment" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            $validation = Validation::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $validation->condition = $request->condition;
            $validation->comment = $request->comment;
            $validation->updated_at = now();
            $validation->save();
            DB::commit();

            $message = "A validated asset has updated";
            $this->storeActivity("Update", $message);
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            
            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just re-validated an asset"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message = "An asset has re-validated";
        $this->storeActivity("Update", $message);

        return redirect()
            ->back()
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();

        try {
            DB::statement('SET CONSTRAINTS ALL DEFERRED');
            Validation::destroy($request->id);
            DB::commit();    
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            
            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just unvalidated an asset"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message = "A validated asset has deleted";
        $this->storeActivity("Delete", $message);

        return redirect()
            ->back()
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function truncate(Request $request)
    {
        $validator = $request->validate([
            "password" => ["required", "string"],
        ]);

        if (!Hash::make($request->password) == Auth::user()->password) {
            return redirect("settings");
        }

        DB::beginTransaction();

        try {
            Validation::where('office_id',$request->office_id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in truncating records");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted all validation records"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity =
            "Danger!, all validation records have just deleted. 1 table is truncated";
        $this->storeActivity("Delete", $activity);

        return redirect()->back()->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function export(Request $request) 
    {
        return Excel::download(new ValidationExport($request->office_name), 'validated_assets.xlsx');
    }
}
