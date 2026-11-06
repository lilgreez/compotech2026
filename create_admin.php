$user = new \App\Models\User();
$user->name = 'Super Admin';
$user->email = 'admin@compotech.com';
$user->password = \Illuminate\Support\Facades\Hash::make('Admin123!');
$user->save();

$role = \App\Models\Role::where('name', 'Admin')->first();
if ($role) {
    $user->roles()->attach($role);
    echo "User created and role attached successfully.";
} else {
    echo "Role Admin not found.";
}
