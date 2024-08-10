<?php

namespace App\Http\Controllers;

use App\Imports\PersonImport;
use App\Models\Asset;
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

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $activities = $this->activity();
        $people = DB::table("persons")
            ->select(
                "persons.*"
            )
            ->where("name","<>","STANDBY")
            ->orderBy("persons.id", "desc")
            ->get();
        $people_count = sizeof($people);

        return view(
            "users.index",
            compact("people", "people_count", "activities")
        );
    }

    public function create()
    {
        $offices = Office::all();

        $activities = $this->activity();

        return view("users.new", compact("offices", "activities"));
    }

    public function store(Request $request)
    {
        $validate = $request->validateWithBag("error", [
            "name" => ["required", "string"],
            "office_id" => ["required", "integer"],
        ]);

        DB::beginTransaction();

        try {
            if ($request->name == "STANDBY") {
                $this->toastNotification("Fails", "User cannot be given name with STANDBY");
                $condition = $this->getCondition();
                $notif = $this->getNotif();

                return redirect()
                    ->back()
                    ->with(["condition" => $condition, "notif" => $notif]);
            }
            $office = Office::where('id', $request->office_id)->first();
            Person::create([
                "name" => $request->name,
                "job_title" => $request->job_title,
                "office_name" => $office->office_name,
                "office_id" => $request->office_id,
                "created_at" => now(),
                "updated_at" => now()
            ]);

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
            "You have just created an person record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "A person has just created with name " .
            $request->name .
            " and office id " .
            $request->office_id;
        $this->storeActivity("Store", $message);

        return redirect('person')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function edit($id)
    {
        $people = Person::find($id);
        $offices = Office::all();

        $activities = $this->activity();

        return view("users.edit", compact("people", "offices", "activities"));
    }

    public function update(Request $request)
    {
        $validate = $request->validateWithBag("error", [
            "id" => ["required"],
            "name" => ["required", "string"],
            "office_id" => ["required", "integer"],
        ]);

        DB::beginTransaction();

        try {
            if ($request->name == "STANDBY") {
                $this->toastNotification("Fails", "User cannot be given name with STANDBY");
                $condition = $this->getCondition();
                $notif = $this->getNotif();

                return redirect()
                    ->back()
                    ->with(["condition" => $condition, "notif" => $notif]);
            }

            $office = Office::where('id',$request->office_id)->first();

            $person = Person::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition
            $person->name = $request->name;
            $person->job_title = $request->job_title;
            $person->office_name = $office->office_name;
            $person->office_id = $request->office_id;
            $person->updated_at = now();

            $history = History::where("person_id", $request->id)
                ->groupBy("id","unique","asset_name","sn","transaction_type","name","office_name","transaction_date","comment","asset_id","person_id","office_id")
                ->orderBy("id", "DESC")
                ->first();
            if ($history <> NULL) {
                if ($history->office_id != $request->office_id) {
                    $asset = Asset::find($history->asset_id);
                    
                    $asset->office_name = $office->office_name;
                    $asset->office_id = $request->office_id;
                    $asset->updated_at = now();
                    $asset->save();
    
                    History::create([
                        "asset_name" => $asset->asset_name,
                        "sn" => $asset->sn,
                        "transaction_type" => $history->transaction_type,
                        "name" => $request->name,
                        "office_name" => $office->office_name,
                        "comment" => "Moving user and asset to new office",
                        "transaction_date" => date("Y-m-d"),
                        "asset_id" => $asset->id,
                        "person_id" => $request->id,
                        "office_id" => $request->office_id,
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);
                } else {
                    $asset = Asset::find($history->asset_id);
                    
                    $asset->office_name = $office->office_name;
                    $asset->office_id = $request->office_id;
    
                    $history->name = $request->name;
                    $history->office_name = $office->office_name;
                    $history->office_id = $office->office_id;
                    $history->updated_at = now();
    
                    $asset->save();
                    $history->save();
                }
            } 
            $person->save();
            
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
            "You have just updated an person record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "A person has just updated with name " .
            $request->name .
            " and office id " .
            $request->id;
        $this->storeActivity("Update", $message);

        return redirect('person')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request)
    {
        $validator = $request->validateWithBag("error", [
            "id" => ["required"]
        ]);

        DB::beginTransaction();

        try {
            $person = Person::find($request->id);

            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("persons")
                ->where("id", $request->id)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in deleting a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted an person record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "A person has just deleted from database with name " .
            $person->name .
            " and office id " .
            $person->office_id;
        $this->storeActivity("Delete", $message);

        return redirect('person')
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
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            Person::truncate();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->toastNotification("Fails", "Failed in truncating records");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect("settings")->with([
                "condition" => $condition,
                "notif" => $notif,
            ]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted all person records"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $activity =
            "Danger!, all person records have just deleted. 1 table is truncated";
        $this->storeActivity("Delete", $activity);

        return redirect("settings")->with([
            "condition" => $condition,
            "notif" => $notif,
        ]);
    }

    public function import(Request $request) {
        $validate = $request->validate([
            'file' => ['required', File::types(['csv'])->max(16000)]
        ]);

        Excel::import(new PersonImport, $request->file->store('temp'),null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function download() {
        return Storage::download("public/template/user.csv");
    }
}
