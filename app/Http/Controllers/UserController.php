<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $activities = $this->activity();
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
        $users = User::all();
        $users_count = $users->count();

        return view(
            "admin.index",
            compact("users", "users_count", "activities")
        );
    }

    public function create()
    { 
        $activities = $this->activity();
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
       
        return view("admin.new", compact("activities"));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
        
        $validate = $request->validateWithBag("error", [
            "name" => ["required", "string"],
            "email" => ["required", "string", "unique:users,email"],
            "password" => ["required", "string", "min:6"],
            "role" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => $request->role,
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

        $this->toastNotification("Success", "Success in storing a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "An admin has just stored with name " .
            $request->name .
            " and email " .
            $request->email .
            " as " .
            $request->role;
        $this->storeActivity("Store", $message);

        return redirect('admin')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function edit($id)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
        $user = User::find($id); 
        $activities = $this->activity();

        return view("admin.edit", compact("user", "activities"));
    }

    public function update(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $validate = $request->validate([
            "id" => ["required", "integer"],
            "name" => ["required", "string"],
            "email" => ["required", "string"],
            "role" => ["required", "string"],
        ]);

        DB::beginTransaction();

        try {
            $user = User::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $email_check = $this->recordCheck(
                $request->email,
                User::class,
                "email",
                $user->email
            );
            if ($email_check == false) {
                return redirect()->back();
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if (strcmp($request->password, $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->updated_at = now();
            $user->save();

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

        $this->toastNotification("Success", "Success in updating a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
        "An admin has just updated from database with email " .
        $user->email;
        $this->storeActivity("Update", $message);

        return redirect()
            ->route('admin')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function editPassword(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
        $user = User::find($request->id); 
        $activities = $this->activity();

        return view("admin.admin_password", compact("user", "activities"));
    }

    public function updatePassword(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        $validate = $request->validate([
            "id" => ["required", "integer"],
            "password" => ["required", "string", "min:6"]
        ]);

        DB::beginTransaction();

        try {
            $user = User::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            if (strcmp(Hash::make($request->password), $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
                $user->updated_at = now();
            }
            $user->save();

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

        $this->toastNotification("Success", "Success in updating a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
        "An admin has just updated from database with email " .
        $user->email;
        $this->storeActivity("Update", $message);

        return redirect()
            ->route('admin')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function delete(Request $request)
    {
        if (Auth::user()->role != "Super admin") {
            $this->toastNotification("Fails", "Failed, you're not allowed to enter this page");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }
        if ($request->id == 1) {
            $this->toastNotification(
                "Fails",
                "Failed in deleting a record. The default admin cannot be deleted"
            );
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        DB::beginTransaction();

        try {
            $user = User::find($request->id);

            DB::statement("SET CONSTRAINTS ALL DEFERRED");
            DB::table("users")
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

        $this->toastNotification("Success", "Success in deleting a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
            "An admin has just deleted from database with email " .
            $user->email;
        $this->storeActivity("Delete", $message);

        return redirect()
            ->back()
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function userProfile()
    {
        $activities = $this->activity();
        $user = User::find(Auth::id());

        return view("admin.profile", compact("user", "activities"));
    }

    public function userProfileEdit($id)
    {
        $activities = $this->activity();
        $user = User::find($id);

        return view("admin.edit_profile", compact("user", "activities"));
    }

    public function userProfileUpdate(Request $request)
    {
        $validate = $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "string"]
        ]);

        DB::beginTransaction();

        try {
            $user = User::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $email_check = $this->recordCheck(
                $request->email,
                User::class,
                "email",
                $user->email
            );
            if ($email_check == false) {
                return redirect()->back();
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->updated_at = now();
            $user->save();

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

        $this->toastNotification("Success", "Success in updating a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
        "An admin has just updated from database with email " .
        $user->email;
        $this->storeActivity("Update", $message);

        return redirect()
            ->route('profile')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function passwordEdit()
    {
        $activities = $this->activity();
        $user = User::find(Auth::id());

        return view("admin.edit_password", compact("user", "activities"));
    }

    public function passwordUpdate(Request $request)
    {
        $validate = $request->validate([
            "password" => ["required", "string" , "min:6"]
        ]);

        DB::beginTransaction();

        try {
            $user = User::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            if (strcmp(Hash::make($request->password), $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
                $user->updated_at = now();
            }
            $user->save();

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

        $this->toastNotification("Success", "Success in updating a record");
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        $message =
        "An admin has just updated from database with email " .
        $user->email;
        $this->storeActivity("Update", $message);

        return redirect()
            ->route('profile')
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function userActivity()
    {
        $activities = $this->activity();
        $data = [];
        if (Auth::user()->role == "Super admin") {
            $data = DB::table("activities as act")
                ->select(
                    "act.id as act_id",
                    "act.actor_id",
                    "users.id",
                    "users.name as actor",
                    "act.token",
                    "act.message",
                    "act.type",
                    "act.is_read",
                    "act.created_at"
                )
                ->leftJoin("users", "users.id", "act.actor_id")
                ->get();
        } else {
            $data = DB::table("activities as act")
                ->select(
                    "act.id as act_id",
                    "act.actor_id",
                    "users.id",
                    "users.name as actor",
                    "act.token",
                    "act.message",
                    "act.type",
                    "act.is_read",
                    "act.created_at"
                )
                ->leftJoin("users", "users.id", "act.actor_id")
                ->where("users.id", Auth::user()->id)
                ->get();
        }
        
        return view("admin.activity", compact("data", "activities"));
    }

    public function readAll()
    {
        if (Auth::user()->role == "Super admin") {
            DB::table('activities')
                ->update([
                    'is_read' => true
                ]);
        } else {
            DB::table('activities')
                ->where('actor_id',Auth::user()->id)
                ->update([
                    'is_read' => true
                ]);
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
            "Success in deleting all activities"
        );
        $condition = $this->getCondition();
        $notif = $this->getNotif();

        return redirect()
            ->back()
            ->with(["condition" => $condition, "notif" => $notif]);
    }

    public function getActivity($id)
    {
        $read = Activity::where('id',$id)->update([
            'is_read' => true,
        ]);

        $data = DB::table("activities as act")
            ->select(
                "act.id as act_id",
                "act.actor_id",
                "users.id",
                "users.name as actor",
                "act.token",
                "act.message",
                "act.type",
                "act.created_at"
            )
            ->leftJoin("users", "users.id", "act.actor_id")
            ->where("act.id", $id)
            ->get();
        $activities = $this->activity();

        return view("admin.activity_detail", compact("data", "activities"));
    }

    protected function updateUser($request, $update_sc = false)
    {
        DB::beginTransaction();

        try {
            $user = User::where("id", $request->id)
                ->lockForUpdate()
                ->first(); // lock for update prevent the race condition

            $email_check = $this->recordCheck(
                $request->email,
                User::class,
                "email",
                $user->email
            );
            if ($email_check == false) {
                return redirect()->back();
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if (strcmp($request->password, $user->password) == 0) {
                $user->password = $user->password;
            } else {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->updated_at = now();
            $user->save();
            DB::commit();

            $update_sc = true;
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->toastNotification("Fails", "Failed in updating a record");
            $condition = $this->getCondition();
            $notif = $this->getNotif();

            return redirect()
                ->back()
                ->with(["condition" => $condition, "notif" => $notif]);
        }

        return $update_sc;
    }
}
