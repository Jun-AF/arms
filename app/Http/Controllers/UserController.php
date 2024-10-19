<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth"); // controller ini menggunakan middleware auth
    }

    public function index()
    {
        $this->is_admin(); // check apakah role user adalah admin

        $activities = $this->activity(); // mengambil record aktivitas user
        $users = User::all();
        $users_count = $users->count();

        return view("admin.index", compact("users", "users_count", "activities"));
    }

    public function create()
    { 
        $this->is_admin(); // check apakah role user adalah admin

        $activities = $this->activity(); // mengambil record aktivitas user
       
        return view("admin.new", compact("activities"));
    }

    public function store(Request $request)
    {
        $this->is_admin(); // check apakah role user adalah admin
        
        $validate = $request->validateWithBag("error", [
            "name" => ["required", "string", "min:1", "max:25", "regex:/[A-Za-z ]/"],
            "email" => ["required", "string", "max:25", "unique:users,email", "regex:/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/"],
            "password" => ["required", "string", "min:6", "max:255", "regex:/([A-Za-z0-9_!*])/"],
            "role" => ["required", "string", Rule::in(["Super admin", "admin"])],
        ]);

        $user = null;
        
        DB::beginTransaction();
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => $request->role,
                "created_at" => now(),
                "updated_at" => now()
            ]);
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();

            $this->toastNotification("Fails", "Connection error, user record with name $request->name can't be stored");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $user->refresh();
        $message = Auth::user()->name. " has created an user record with id " . $user->id;
        $this->storeActivity("Store", $message);

        $this->toastNotification("Success", "Record has stored");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('admin')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function edit($id)
    {
        $this->is_admin(); // check apakah role user adalah admin

        $activities = $this->activity(); // mengambil record aktivitas user

        $user = User::find($id); 
    
        return view("admin.edit", compact("user", "activities"));
    }

    public function update(Request $request)
    {
        $this->is_admin(); // check apakah role user adalah admin

        $validate = $request->validate([
            "id" => ["required", "integer"],
            "name" => ["required", "string", "min:1", "max:25", "regex:/[A-Za-z ]/"],
            "email" => ["required", "string", "max:25", "unique:users,email,$request->id", "regex:/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/"],
            "role" => ["required", "string", Rule::in(["Super admin", "admin"])],
        ]);

        DB::beginTransaction();
        try {
            $user = User::where("id", $request->id)->lockForUpdate()->first(); // lock for update prevent the race condition

            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->updated_at = now();
            $user->save(); // save user update
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();

            $this->toastNotification("Fails", "Connection error, user record with id $request->id can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = Auth::user()->name. " has updated an user record with id " . $user->id;
        $this->storeActivity("Update", $message);

        $this->toastNotification("Success", "Record has updated");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('admin')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function editPassword(Request $request)
    {
        $this->is_admin(); // check apakah role user adalah admin

        $activities = $this->activity(); // mengambil record aktivitas user

        $user = User::find($request->id); 

        return view("admin.admin_password", compact("user", "activities"));
    }

    public function updatePassword(Request $request)
    {
        $this->is_admin(); // check apakah role user adalah admin

        $validate = $request->validate([
            "id" => ["required", "integer"],
            "password" => ["required", "string", "min:6", "max:255", "regex:/([A-Za-z0-9_!*])/"],
        ]);

        DB::beginTransaction();
        try {
            $user = User::where("id", $request->id)->lockForUpdate()->first(); // lock for update prevent the race condition

            if (strcmp(Hash::make($request->password), $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
                $user->updated_at = now();
            }
            $user->save(); // save user update
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();

            $this->toastNotification("Fails", "Connection error, user's password with id $request->id can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = Auth::user()->name. " has updated an user's password with id " . $user->id;
        $this->storeActivity("Update", $message);

        $this->toastNotification("Success", "User's password has updated");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('admin')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request)
    {
        $this->is_admin(); // check apakah role user adalah admin

        if ($request->id == 1) {
            $this->toastNotification("Fails", "Default admin cannot be deleted");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }

        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("users")->where("id", $request->id)->delete();
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, user record with id $request->id can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = Auth::user()->name. " has deleted an user record with id " . $user->id;
        $this->storeActivity("Delete", $message);

        $this->toastNotification("Success", "User has deleted");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect('admin')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function userProfile()
    {
        $activities = $this->activity(); // mengambil record aktivitas user

        $user = User::find(Auth::id());

        return view("admin.profile", compact("user", "activities"));
    }

    public function userProfileEdit($id)
    {
        $activities = $this->activity(); // mengambil record aktivitas user

        $user = User::find($id);

        return view("admin.edit_profile", compact("user", "activities"));
    }

    public function userProfileUpdate(Request $request)
    {
        $validate = $request->validate([
            "name" => ["required", "string", "min:1", "max:25", "regex:/[A-Za-z ]/"],
            "email" => ["required", "string", "max:25", "unique:users,email,$request->id", "regex:/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/"]
        ]);

        DB::beginTransaction();
        try {
            $user = User::where("id", $request->id)->lockForUpdate()->first(); // lock for update prevent the race condition
            $user->name = $request->name;
            $user->email = $request->email;
            $user->updated_at = now();
            $user->save(); // save user update
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();
            $this->toastNotification("Fails", "Connection error, your profile can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = "You have updated your profile";
        $this->storeActivity("Update", $message);

        $this->toastNotification("Success", "Your profile has updated");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        return redirect()->route('profile')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function passwordEdit()
    {
        $activities = $this->activity(); // mengambil record aktivitas user

        $user = User::find(Auth::id());

        return view("admin.edit_password", compact("user", "activities"));
    }

    public function passwordUpdate(Request $request)
    {
        $validate = $request->validate([
            "id" => ["required", "integer"],
            "password" => ["required", "string", "min:6", "max:255", "regex:/([A-Za-z0-9_!*])/"],
        ]);

        DB::beginTransaction();
        try {
            $user = User::where("id", $request->id)->lockForUpdate()->first(); // lock for update prevent the race condition

            if (strcmp(Hash::make($request->password), $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
                $user->updated_at = now();
            }
            $user->save(); // save user update
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();

            $this->toastNotification("Fails", "Connection error, your password can't be updated");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = "Your have updated your password";
        $this->storeActivity("Update", $message);

        $this->toastNotification("Success", "Your password has updated");
        $condition = $this->getCondition();
        $notif = $this->getNotif();
        
        return redirect()->route('profile')->with(["condition" => $condition, "notif" => $notif]);
    }

    public function userActivity()
    {
        $activities = $this->activity(); // mengambil record aktivitas user

        $data = [];
        if (Auth::user()->role == "Super admin") {
            $data = DB::table("activities as act")->select(
                    "act.id as act_id",
                    "act.actor_id",
                    "users.id",
                    "users.name as actor",
                    "act.token",
                    "act.message",
                    "act.type",
                    "act.is_read",
                    "act.created_at"
                )->leftJoin("users", "users.id", "act.actor_id")->get();
        } else {
            $data = DB::table("activities as act")->select(
                    "act.id as act_id",
                    "act.actor_id",
                    "users.id",
                    "users.name as actor",
                    "act.token",
                    "act.message",
                    "act.type",
                    "act.is_read",
                    "act.created_at"
                )->leftJoin("users", "users.id", "act.actor_id")->where("users.id", Auth::user()->id)->get();
        }
        
        return view("admin.activity", compact("data", "activities"));
    }

    public function readAll()
    {
        if (Auth::user()->role == "Super admin") {
            DB::beginTransaction();
            try {
                DB::table('activities')->update([
                    'is_read' => true
                ]);
                DB::commit();    
            } catch (QueryException $err) {
                DB::rollBack();
    
                $this->toastNotification("Fails", "Connection error");
                $condition = $this->getCondition();
                $notif = $this->getNotif();
                return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
            }
        } else {
            DB::beginTransaction();
            try {
                DB::table('activities')->where('actor_id',Auth::user()->id)->update([
                    'is_read' => true
                ]);
                DB::commit();
            } catch (QueryException $err) {
                DB::rollBack();
    
                $this->toastNotification("Fails", "Connection error");
                $condition = $this->getCondition();
                $notif = $this->getNotif();
                return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
            }
        }
            
        return redirect()->route('activity');
    }

    public function truncateActivity()
    {
        DB::beginTransaction();
        try {
            if (Auth::user()->role == "Super admin") {
                Activity::truncate();
            } else {
                Activity::where('actor_id',Auth::user()->id)->delete();
            }
            DB::commit();
        } catch (QueryException $err) {
            DB::rollBack();
            
            $this->toastNotification("Fails", "Connection error, activities can't be cleaned");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
            return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
        }
        $message = "Activities have cleaned";
        $this->storeActivity("Delete", $message);

        $this->toastNotification("Success", "Activities have cleaned");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        return redirect()->back()->with(["condition" => $condition, "notif" => $notif]);
    }

    public function getActivity($id)
    {
        $condition = "";
        $notif = "";

        DB::beginTransaction();
        try {
            $read = Activity::where('id',$id)->update([
                'is_read' => true,
            ]);
            DB::commit();
        } catch(QueryException $err) {
            DB::rollBack();

            $this->toastNotification("Fails", "Connection error");
            $condition = $this->getCondition();
            $notif = $this->getNotif();
        }
        $data = DB::table("activities as act")->select(
                "act.id as act_id",
                "act.actor_id",
                "users.id",
                "users.name as actor",
                "act.token",
                "act.message",
                "act.type",
                "act.created_at"
            )->leftJoin("users", "users.id", "act.actor_id")->where("act.id", $id)->get();

        $activities = $this->activity(); // mengambil record aktivitas user

        return view("admin.activity_detail", compact("data", "activities"))->with(["condition" => $condition, "notif" => $notif]);
    }
}
