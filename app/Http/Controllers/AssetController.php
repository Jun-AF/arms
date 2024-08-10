<?php

namespace App\Http\Controllers;

use App\Imports\AssetImport;
use App\Models\Asset;
use App\Models\History;
use App\Models\Office;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Shows the assets index table
     *
     * Join the assets, histories, and offices table
     */
    public function index()
    {
        $activities = $this->activity();
        $assets = DB::table("assets")
            ->select(
                "assets.*"
            )
            ->groupBy("assets.id",'assets.uniqueid',"assets.asset_name","assets.type","assets.sn","assets.mac_address","assets.office_name","assets.purchase_date","assets.asset_in","assets.office_id")
            ->orderBy("assets.id", "ASC")
            ->get();
        $assets_count = sizeof($assets);

        return view(
            "assets.index",
            compact("assets", "assets_count", "activities")
        );
    }

    /**
     * Presents an asset record in the table by searching it with serial number
     */
    public function search(Request $request)
    {
        $activities = $this->activity();
        $assets = DB::table("assets")
            ->select(
                "assets.*"
            )
            ->where("sn", $request->sn)
            ->get();
        $assets_count = Asset::all()->count();

        return view(
            "assets.index",
            compact("assets", "assets_count", "activities")
        );
    }

    public function create()
    {
        $type = ['Laptop','PC','Monitor','IP Camera','Surveilance','Attendance','Others'];
        $activities = $this->activity();
        $offices = Office::all();

        return view("assets.new", compact("offices", "activities", "type"));
    }

    /**
     * Store a new asset record and make a new transaction record with a STANDBY user
     *
     */
    public function store(Request $request)
    {
        switch ($request->type) {
            case 'Laptop':
            case 'PC':
                $validator = $request->validateWithBag("error", [
                    "asset_name" => ["required", "string", "max:50"],
                    "type" => ["required", "string"],
                    "sn" => ["required", "string", "unique:assets,sn"],
                    "os" => ["required", "string"],
                    "hostname" => ["required", "string"],
                    "mac_address" => ["string"],
                    "purchase_date" => ["date"],
                    "office_id" => ["required", "integer"],
                    "asset_in" => ["date"]
                ]);
                break;
            default:
                $validator = $request->validateWithBag("error", [
                    "asset_name" => ["required", "string", "max:50"],
                    "type" => ["required", "string"],
                    "sn" => ["required", "string", "unique:assets,sn"],
                    "purchase_date" => ["date"],
                    "office_id" => ["required", "integer"],
                    "asset_in" => ["date"]
                ]);
                break;
        }
        
        DB::beginTransaction();

        try {
            // Declaration & Initialization Variable
            $asset = Asset::where('asset_name', $request->asset_name)->first();
            $person = Person::where("office_id", $request->office_id)
            ->where("persons.name", "STANDBY")
            ->first();
            $uniqueid =
                $person->office_name."-".random_int(1, 1000000);
            $mac = str_replace("-", ":", $request->mac_address);
            // Record Insertion Processes
            $asset = Asset::create([
                "uniqueid" => $uniqueid,
                "asset_name" => $request->asset_name,
                "type" => $request->type,
                "sn" => $request->sn,
                "os" => $request->os,
                "hostname" => $request->hostname,
                "mac_address" => $mac,
                "purchase_date" => date("Y-m-d", strtotime($request->asset_in)),
                "office_name" => $person->office_name,
                "asset_in" => date("Y-m-d", strtotime($request->asset_in)),
                "office_id" => $person->office_id,
                "created_at" => now(),
                "updated_at" => now()
            ]);
            $asset->refresh();
            History::create([
                "uniqueid" => $asset->uniqueid,
                "asset_name" => $asset->asset_name,
                "sn" => $asset->sn,
                "transaction_type" => "Handover",
                "name" => $person->name,
                "office_name" => $person->office_name,
                "comment" => "New asset",
                "transaction_date" => date("Y-m-d", strtotime($request->asset_in)),
                "asset_id" => $asset->id,
                "person_id" => $person->id,
                "office_id" => $person->office_id,
                "created_at" => now(),
                "updated_at" => now()
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in storing record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just created an asset record"
        );

        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message = "An asset has just created with uniqueid " . $uniqueid;
        $this->storeActivity("Store", $message);

        return redirect('asset')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    /**
     * Presents asset properties and only shows its office name if the user isn't STANDBY
     *
     */
    public function edit($id, $offices = null)
    {
        $type = ['Laptop','PC','Monitor','IP Camera','Surveilance','Attendance','Others'];
        $activities = $this->activity();
        $asset = DB::table(
            DB::raw(
                "(SELECT DISTINCT * FROM histories ORDER BY id DESC) histories"
            )
        )
            ->select("histories.*", "assets.*")
            ->leftJoin("assets", "assets.id", "histories.asset_id")
            ->where("assets.id", $id)
            ->groupBy("histories.id","histories.sn","histories.uniqueid","histories.asset_name","histories.transaction_type","histories.name","histories.office_name","histories.transaction_date","histories.comment","histories.asset_id","histories.person_id","histories.office_id","histories.created_at","histories.updated_at","assets.id")
            ->orderBy("histories.id", "DESC")
            ->get();
        if ($asset[0]->name == "STANDBY") {
            $offices = Office::all();
        } else {
            $offices = Office::where('id',$asset[0]->office_id)->get();
        }

        return view("assets.edit", compact("asset", "offices", "activities","type"));
    }

    public function update(Request $request)
    {
        switch ($request->type) {
            case 'Laptop':
            case 'PC':
                $validator = $request->validateWithBag("error", [
                    "asset_name" => ["required", "string", "max:50"],
                    "type" => ["required", "string"],
                    "sn" => ["required", "string"],
                    "os" => ["required", "string"],
                    "hostname" => ["required", "string"],
                    "mac_address" => ["string"],
                    "purchase_date" => ["date"],
                    "office_id" => ["required", "integer"],
                    "asset_in" => ["date"]
                ]);
                break;
            default:
                $validator = $request->validateWithBag("error", [
                    "asset_name" => ["required", "string", "max:50"],
                    "type" => ["required", "string"],
                    "sn" => ["required", "string", "unique:assets,sn"],
                    "purchase_date" => ["date"],
                    "office_id" => ["required", "integer"],
                    "asset_in" => ["date"]
                ]);
                break;
        }

        DB::beginTransaction();

        try {
            // Declaration & Initialization Variable
            $mac = str_replace("-", ":", $request->mac_address);
            $office = Office::where('id', $request->office_id)->first();
            $asset = Asset::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition
            $sn_check = $this->recordCheck(
                $request->sn,
                Asset::class,
                "sn",
                $asset->sn
            );

            // SN Check
            if ($sn_check == false) {
                $this->toastNotification(
                    "Fails",
                    "There is a record with this serial number"
                );
                $condition = $this->getCondition();
                $notif = $this->getNotif();

                return redirect()
                    ->back()
                    ->with(["condition" => $condition, "notif" => $notif]);
            }

            // Update Record Processes
            $asset->asset_name = $request->asset_name;
            $asset->type = $request->type;
            $asset->sn = $request->sn;
            $asset->os = $request->os;
            $asset->hostname = $request->hostname;
            $asset->mac_address = $mac;
            $asset->purchase_date = date(
                "Y-m-d",
                strtotime($request->asset_in)
            );
            $asset->office_name = $office->office_name;
            $asset->asset_in = date("Y-m-d", strtotime($request->asset_in));
            $asset->office_id = $request->office_id;
            $asset->updated_at = now();

            if (($request->office_id == $asset->office_id ? 1 : 0) == 0) {
                $history = History::select(DB::raw("DISTINCT *"))
                    ->where("asset_id", $request->id)
                    ->groupBy("id","uniqueid","asset_name","sn","transaction_type","name","office_name",        "transaction_date","comment","asset_id","person_id","office_id")
                    ->orderBy("transaction_date", "DESC")
                    ->lockForUpdate()
                    ->first();
                $history->asset_name = $request->asset_name;
                $history->sn = $request->sn;
                $history->office_name = $office->office_name;
                $history->office_id = $request->office_id;
                $history->updated_at = now();
                $history->save();
            }
            $asset->save();

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
            "You have just updated an asset record"
        );

        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message = "An asset has just updated with uniqueid " . $asset->uniqueid;
        $this->storeActivity("Update", $message);

        return redirect('asset')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    /**
     * Deleting an asset can be done without affecting any tables which related to the asset table
     *
     */
    public function delete(Request $request)
    {
        $validator = $request->validate([
            "id" => ["required"]
        ]);

        DB::beginTransaction();

        try {
            $asset = Asset::find($request->id);

            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("assets")
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
            "You have just deleted an asset record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "An asset has just deleted from database with uniqueid " .
            $asset->uniqueid;
        $this->storeActivity("Delete", $message);

        return redirect('asset')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function import(Request $request) {
        $validate = $request->validate([
            'file' => ['required', File::types(['csv'])->max(16000)]
        ]);

        Excel::import(new AssetImport, $request->file->store('temp'),null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function download() {
        return Storage::download("public/template/asset.csv");
    }
}
