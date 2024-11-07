<?php

namespace App\Http\Controllers;

use App\Imports\OfficeImport;
use App\Models\History;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $activities = $this->activity();
        $offices = Office::all();
        $offices_count = $offices->count();

        return view(
            "offices.index",
            compact("offices", "offices_count", "activities")
        );
    }

    public function create()
    {
        $activities = $this->activity();

        return view("offices.new", compact("activities"));
    }

    public function store(Request $request)
    {
        $validate = $request->validateWithBag("error", [
            "office_name" => [
                "required",
                "string",
                "unique:offices,office_name"
            ],
            "location" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            $office = Office::create([
                "office_name" => $request->office_name,
                "location" => $request->location,
                "created_at" => now(),
                "updated_at" => now()
            ]);
            $office->refresh();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in storing a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just created an office record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "An office has just created with office name " .
            $request->office_name;
        $this->storeActivity("Store", $message);

        return redirect("office")->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function edit($id)
    {
        $activities = $this->activity();
        $offices = Office::find($id);

        return view("offices.edit", compact("offices", "activities"));
    }

    public function update(Request $request)
    {
        $validate = $request->validateWithBag("error", [
            "id" => ["required"],
            "office_name" => ["required", "string"],
            "location" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            $office = Office::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $office_check = $this->recordCheck(
                $request->office_name,
                Office::class,
                "office_name",
                $office->office_name
            );
            if ($office_check == false) {
                $this->toastNotification(
                    "Fails",
                    "There is a record with this name"
                );
                $condition = $this->getCondition();
                $notif = $this->getNotif();

                return redirect()
                    ->back()
                    ->with(["condition" => $condition, "notif" => $notif]);
            }

            $office->office_name = $request->office_name;
            $office->location = $request->location;
            $office->updated_at = now();
            $office->save();

            Person::where('office_id',$request->id)->update([
                "office_name" => $request->office_name,
                "updated_at" => now()
            ]);

            $histories = DB::table(DB::raw("(SELECT * FROM histories WHERE office_id = '".$request->id."' ORDER BY id DESC) h"))->select("h.*")
                ->groupBy("h.id","h.sn","h.unique","h.asset_name","h.transaction_type","h.name","h.office_name","h.transaction_date","h.comment","h.asset_id","h.person_id","h.office_id","h.created_at","h.updated_at")
                ->orderBy("h.transaction_date", "DESC")
                ->get();

            foreach ($histories as $h) {
                History::where()->update([
                    'office_name' => $request->office_name,
                    'updated_at' => now()
                ]);
            }
            
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
            "You have just updated an office record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "An office has just updated with id " .
            $request->id .
            " and office name " .
            $request->office_name;
        $this->storeActivity("Update", $message);

        return redirect("office")->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function truncate(Request $request)
    {
        $validator = $request->validate([
            "password" => ["required", "string"],
        ]);

        if (!Hash::make($request->password) == Auth::user()->password) {
            $this->toastNotification("Fails", "Failed in truncating record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect("settings")->with([
                "condition" => $condition,
                "notif" => $notif,
            ]);
        }

        DB::beginTransaction();
        
        try {
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            Office::truncate();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            
            $this->toastNotification("Fails", "Failed in truncating record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect("settings")->with([
                "condition" => $condition,
                "notif" => $notif,
            ]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted all office records"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity =
            "Danger!, all office records have just deleted. 1 table is truncated";
        $this->storeActivity("Delete", $activity);

        return redirect("settings")->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function delete(Request $request)
    {
        $validator = $request->validate([
            "password" => ["required", "string"],
        ]);

        if (!Hash::make($request->password) == Auth::user()->password) {
            $this->toastNotification("Fails", "Failed in deleting record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->bakc()
                ->with([
                    "condition" => $condition,
                    "notif" => $notif,
                ]);
        }

        DB::beginTransaction();

        try {
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("offices")
                ->where("id", $request->id)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in deleting a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect("office")->with([
                "condition" => $condition,
                "notif" => $notif,
            ]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted an office record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity = "An office has just deleted from database with id " . $request->id;
        $this->storeActivity("Delete", $activity);

        return redirect("office")->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function import(Request $request) {
        $validate = $request->validate([
            'file' => ['required', File::types(['csv'])->max(16000)]
        ]);

        Excel::import(new OfficeImport, $request->file->store('temp'),null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function download() {
        return Storage::download("public/template/office.csv");
    }
}
