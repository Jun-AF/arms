<?php
namespace App\Http\Controllers;
use App\Models\Person;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class PersonController extends Controller {
    public function __construct() {
        $this->middleware("auth");
    }

    public function index() {
        $activities = $this->activity();
        $people = DB::table("persons")->select("persons.*")->where("name", "<>", "STANDBY")->orderBy("persons.id", "desc")->get();
        $people_count = sizeof($people);
        return view("users.index", compact("people", "people_count", "activities"));
    }

    public function create() {
        $activities = $this->activity();
        return view("users.new", compact("activities"));
    }

    public function store(Request $request) {
        $validate = $request->validateWithBag("error", ["name" => ["required", "string", "regex:/^[A-Z][a-z].[0-9]/"], "job_title" => ["required", "string", "regex:/^[A-Z][a-z].[0-9]/"]]);
        $person = null;
        DB::beginTransaction();
        try {
            $person = Person::create(["name" => $request->name, "job_title" => $request->job_title, "created_at" => now() ]);
            DB::commit();
        }
        catch(QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, person record with name $request->name can't be stored");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $person->refresh();
        $message = Auth::user()->name . " has created a person record with id " . $person->id;
        $this->storeActivity("Store", $message);
        $this->toastNotification("Success", "Record has stored");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('person')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function edit($id) {
        $activities = $this->activity();
        $people = Person::find($id);
        return view("users.edit", compact("people", "activities"));
    }

    public function update(Request $request) {
        $validate = $request->validateWithBag("error", ['id' => ["required", "integer", "exists:App\Model\Person,id"], "name" => ["required", "string", "regex:/^[A-Z][a-z].[0-9]/"], "job_title" => ["required", "string", "regex:/^[A-Z][a-z].[0-9]/"]]);
        $person = null;
        DB::beginTransaction();
        try {
            $person = Person::where("id", $request->id)->lockForUpdate()->first(); // lock for update prevent the race condition
            $person->name = $request->name;
            $person->job_title = $request->job_title;
            $person->updated_at = now();
            $person->save();
            
            DB::commit();
        }
        catch(QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, person record with name $request->name can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $person->refresh();
        $message = Auth::user()->name . " has updated a person record with id " . $person->id;
        $this->storeActivity("Update", $message);
        $this->toastNotification("Success", "Record has updated");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('person')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request) {
        $validator = $request->validateWithBag("error", ['id' => ["required", "integer", "exists:App\Model\User,id"], ]);
        $person = null;
        DB::beginTransaction();
        try {
            $person = Person::find($request->id);
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("persons")->where("id", $request->id)->delete();
            DB::commit();
        }
        catch(QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, person record with id $request->id can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = Auth::user()->name . " has deleted a person record with id " . $person->id;
        $this->storeActivity("Delete", $message);
        $this->toastNotification("Success", $person->name . " has deleted");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('admin')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function truncate(Request $request) {
        $validator = $request->validate(["password" => ["required", "string", "min:6", "max:255", "regex:/([A-Za-z0-9_!*])/"], ]);
        if (!Hash::make($request->password) == Auth::user()->password) {
            return redirect("settings");
        }
        DB::beginTransaction();
        try {
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            Person::truncate();
            DB::commit();
        }
        catch(QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, deletion all person cannot be done");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = "You have updated your profile";
        $this->storeActivity("Update", $message);
        $this->toastNotification("Success", "All person has deleted");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect()->route('profile')->with(["condition" => $condition, "notif" => $notif]);
    }
}
