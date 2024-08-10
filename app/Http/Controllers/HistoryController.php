<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Imports\HistoryImport;
use App\Models\Asset;
use App\Models\History;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Presenting the transaction records only each last transaction of them
     *
     */
    public function index()
    {
        $activities = $this->activity();
        
        $stb = 0; $non_stb = 0;
        $histories = DB::table(
            DB::raw(
                "(SELECT DISTINCT ON (uniqueid) * FROM histories ORDER BY uniqueid,transaction_date DESC) AS h"
            )
        )
            ->select(
                "h.*"
            )
            ->get();

        foreach ($histories as $tr) {
            ($tr->name == "STANDBY") ? ++$stb : ++$non_stb;
        }
        return view(
            "assets.histories.index",
            compact("histories", "stb", "non_stb", "activities")
        );
    }

    public function detail($uniqueid)
    { 
        $activities = $this->activity();
        $asset = Asset::where('uniqueid',$uniqueid)->first();
        $histories = History::where('uniqueid',$uniqueid)->orderBy('transaction_date','DESC')->get();

        if (sizeof($histories) == 0) {
            $this->toastNotification("Fails", "No transaction with this asset");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect('transaction')
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        return view(
            "assets.histories.search",
            compact("asset", "histories", "activities")
        );
    }

    public function create()
    {
        $activities = $this->activity();
        $people = Person::with('office')->get();
        $assets = Asset::all();

        return view(
            "assets.histories.new",
            compact("people", "assets", "activities")
        );
    }

    /**
     * Creating a transaction record with user office location
     *
     * Updating asset office location with user location
     */
    public function store(Request $request)
    {
        $validator = $request->validateWithBag("error", [
            "asset_id" => ["required", "integer"],
            "transaction_type" => ["required", "string"],
            "person_id" => ["required", "integer"],
            "comment" => ["required", "string"],
            "transaction_date" => ["required", "date"],
        ]);

        DB::beginTransaction();

        try {
            $person = Person::where('id',$request->person_id)->first();
            $asset = Asset::where("id", $request->asset_id)
                ->lockForUpdate()
                ->first();

            $asset->office_name = $person->office_name;
            $asset->office_id = $person->office_id;

            History::create([
                "uniqueid" => $asset->uniqueid,
                "asset_name" => $asset->asset_name,
                "sn" => $asset->sn,
                "transaction_type" => $request->transaction_type,
                "name" => $person->name,
                "office_name" => $person->office_name,
                "comment" => $request->comment,
                "transaction_date" => date(
                    "Y-m-d",
                    strtotime($request->transaction_date)
                ),
                "asset_id" => $request->asset_id,
                "person_id" => $request->person_id,
                "office_id" => $person->office_id,
                "created_at" => now(),
                "updated_at" => now()
            ]);

            $asset->save();

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
            "You have just created a transaction record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just created with asset id ' .
            $request->asset_id;
        $this->storeActivity("Store", $message);

        return redirect('transaction')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function edit($id)
    {
        $activities = $this->activity();
        $history = History::find($id);
        $people = Person::all();
        $assets = Asset::all();

        return view(
            "assets.histories.edit",
            compact("history", "people", "assets", "activities")
        );
    }

    /**
     * Updating transaction affecting asset location by user location
     *
     */
    public function update(Request $request)
    {
        $validator = $request->validateWithBag("error", [
            "id" => ["required"],
            "asset_id" => ["required", "integer"],
            "transaction_type" => ["required", "string"],
            "person_id" => ["required", "integer"],
            "comment" => ["required", "string"],
            "transaction_date" => ["required", "date"],
        ]);

        DB::beginTransaction();

        try {
            $person = Person::where('id',$request->person_id)->first();
            $asset = Asset::where("id", $request->asset_id)
                ->lockForUpdate()
                ->first();

            $asset->office_name = $person->office_name;
            $asset->office_id = $person->office_id;
            $asset->updated_at = now();

            $history = History::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $history->uniqueid = $asset->uniqueid;
            $history->asset_name = $asset->asset_name;
            $history->sn = $asset->sn;
            $history->transaction_type = $request->transaction_type;
            $history->name = $person->name;
            $history->office_name = $person->office_name;
            $history->comment = $request->comment;
            $history->transaction_date = date(
                "Y-m-d",
                strtotime($request->transaction_date)
            );
            $history->asset_id = $request->asset_id;
            $history->person_id = $request->person_id;
            $history->office_id = $person->office_id;
            $history->updated_at = now();

            $asset->save();
            $history->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just updated a transaction record"
        );
        
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just updated with asset id ' .
            $request->asset_id;
        $this->storeActivity("Store", $message);

        return redirect('transaction')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request)
    {
        $validator = $request->validateWithBag("error", [
            "id" => ["required"]
        ]);

        DB::beginTransaction();

        try {
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("histories")
                ->where("id", $request->id)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in deleting a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect('transaction')
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted a transaction record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just updated with asset id ' . $request->id;
        $this->storeActivity("Delete", $message);

        return redirect('transaction')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function detailCreate($uniqueid) 
    {
        $activities = $this->activity();
        $people = Person::with('office')->get();
        $assets = Asset::where('uniqueid',$uniqueid)->first();

        return view(
            "assets.histories.detail.new",
            compact("people", "assets", "uniqueid", "activities")
        );
    }

    public function detailStore(Request $request, $uniqueid) 
    {
        $validator = $request->validateWithBag("error", [
            "transaction_type" => ["required", "string"],
            "person_id" => ["required", "integer"],
            "comment" => ["required", "string"],
            "transaction_date" => ["required", "date"],
        ]);

        DB::beginTransaction();

        try {
            $person = Person::where('id',$request->person_id)->first();
            $asset = Asset::where("uniqueid", $uniqueid)
                ->lockForUpdate()
                ->first();

            $asset->office_name = $person->office_name;
            $asset->office_id = $person->office_id;
            $asset->updated_at = now();

            History::create([
                "uniqueid" => $uniqueid,
                "asset_name" => $asset->asset_name,
                "sn" => $asset->sn,
                "transaction_type" => $request->transaction_type,
                "name" => $person->name,
                "office_name" => $person->office_name,
                "comment" => $request->comment,
                "transaction_date" => date(
                    "Y-m-d",
                    strtotime($request->transaction_date)
                ),
                "asset_id" => $request->asset_id,
                "person_id" => $request->person_id,
                "office_id" => $person->office_id,
                "created_at" => now(),
                "updated_at" => now()
            ]);

            $asset->save();

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
            "You have just created a transaction record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just created with asset id ' .
            $request->asset_id;
        $this->storeActivity("Store", $message);

        return redirect('transaction/'.$uniqueid)
            ->with(["condition" => $condition, "notif" => $notif]);
    }
    
    public function detailEdit($uniqueid,$id) 
    {
        $activities = $this->activity();
        $history = History::find($id);
        $people = Person::all();
        $assets = Asset::where('uniqueid',$uniqueid)->first();

        return view(
            "assets.histories.detail.edit",
            compact("history", "people", "assets", "uniqueid", "activities")
        );
    }
    
    public function detailUpdate(Request $request, $uniqueid) 
    {
        $validator = $request->validateWithBag("error", [
            "id" => ["required"],
            "transaction_type" => ["required", "string"],
            "person_id" => ["required", "integer"],
            "comment" => ["required", "string"],
            "transaction_date" => ["required", "date"],
        ]);

        DB::beginTransaction();

        try {
            $person = Person::where('id',$request->person_id)->first();
            $asset = Asset::where("id", $uniqueid)
                ->lockForUpdate()
                ->first();

            $asset->office_name = $person->office_name;
            $asset->office_id = $person->office_id;
            $asset->updated_at = now();

            $history = History::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition
            $history->transaction_type = $request->transaction_type;
            $history->name = $person->name;
            $history->office_name = $person->office_name;
            $history->comment = $request->comment;
            $history->transaction_date = date(
                "Y-m-d",
                strtotime($request->transaction_date)
            );
            $history->person_id = $request->person_id;
            $history->office_id = $person->office_id;
            $history->updated_at = now();

            $asset->save();
            $history->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just updated a transaction record"
        );
        
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just updated with asset id ' .
            $request->asset_id;
        $this->storeActivity("Store", $message);

        return redirect('transaction/'.$uniqueid)
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function detailDelete($uniqueid, $id) 
    {
        DB::beginTransaction();

        try {
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("histories")
                ->where("id", $id)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in deleting a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect('transaction/detail/'.$uniqueid)
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $this->toastNotification(
            "Success",
            "You have just deleted a transaction record"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            'An asset\'s transaction has just updated with asset id ' . $id;
        $this->storeActivity("Delete", $message);

        return redirect('transaction/detail/'.$uniqueid)
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function export() 
    {
        return Excel::download(new TransactionExport, 'asset_transactions.xlsx');
    }

    public function import(Request $request) {
        $validate = $request->validate([
            'file' => ['required', File::types(['csv'])->max(16000)]
        ]);

        Excel::import(new HistoryImport, $request->file->store('temp'),null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function download() {
        return Storage::download("public/template/transaction.csv");
    }
}
