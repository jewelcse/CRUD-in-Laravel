<?php

use Illuminate\Support\Facades\Route;
use App\User;
use App\Address;
use App\Post;
use App\Role;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
 *  One to One relationship
 */
Route::get('/insert-data',function (){

   $user = User::find(1);

   $address = new Address(['name'=>'Kotalipara Gopalgonj']);

   $user->address()->save($address);

});


Route::get('/update-address',function (){

   // $user = User::findOrFail(1);

//    $address = Address::where('user_id',1)->get();
//    $address = Address::where('user_id','=',1)->get();
//    $address = Address::whereUserId(1)->get();
    $address = Address::whereUserId(1)->first();

    $address->name = 'Updated address';
    $address->save();

//    return $address;



});

Route::get('/read',function (){

    /*
     * Right way to fetch using user
     */
    $user = User::findOrFail(1);
    return $user->address->name;

    /*
     * Wrong way to fetch , directly lookup the address table
     */
//   $address = Address::findOrFail(1);
//   return $address->name;
});

Route::get('/delete',function (){

    /*
     * Right way to delete
     */
    $user = User::findOrFail(1);
    $user->address()->delete();

    /*
     * Wrong way to delete
     */
//    $address = Address::findOrFail(1);
//    $address->delete();

});

/*
 * One to Many relationship
 */


Route::get('/insert-data-2',function (){

    $user = User::findOrFail(1);

    $post = new Post(['title'=>'This is title','user_id'=>1,'body'=>'this is body']);

    $user->posts()->save($post);

});


Route::get('/update-2/{uid}/{pid}',function ($uid,$pid){

    $user = User::findOrFail($uid);

//    return $user->posts;

//    $post = Post::whereId($pid)->whereUserId($uid)->get();
//
//    $post->title='Updated title';
//    $post->save();
   $user->posts()->whereId($pid)->update(['title'=>'updated title']);

});


Route::get('/readmany/{uid}',function ($uid){
    $user = User::find($uid);
    return $user->posts;
});

Route::get('/deletemamy/{uid}/{pid}',function ($uid,$pid){
    $user = User::findOrFail($uid);
    $user->posts()->whereId($pid)->delete();
});


/*
 * Many to Many Relationship
 */

Route::get('create-role',function (){
    $user = User::findOrFail(1);
    $role = new Role(['name'=>'Contributor']);
    $user->roles()->save($role);
});


Route::get('/read-role',function (){

    $user = User::findOrFail(1);
    foreach ($user->roles as $role){
        echo $role->name."<br>";
    }
});


Route::get('/update-role',function (){

    $user = User::findOrFail(1);
    //$user->roles()->where('role_id',1)->update(['name'=>'contributor']); // one way

    if ($user->has('roles')){ // anoher way
        foreach ($user->roles as $role){
            if ($role->name == 'contributor'){
                $role->name = 'administrator';
                $role->save();

            }
        }
    }

});


Route::get('/delete-role',function (){

    $user = User::findOrFail(1);

    foreach ($user->roles as $role){
        //dd($role);
        $role->whereId(2)->delete();
    }
});

/*
 * attaching, detaching and syncing
 */

Route::get('/attach',function (){

    $user = User::find(5);
    $user->roles()->attach(4); // always expected one parameter

});

Route::get('/detach',function (){

    $user = User::find(1);
    $user->roles()->detach(); // delete all records
    $user->roles()->detach(4); // delete only uid-1 and rid-4

});

Route::get('/sync',function (){

    $user = User::findOrFail(1);
    $user->roles()->sync([3,4,5]); // added this at a time
    $user->roles()->sync([3]); // if i execute afted added then it removes 4 and 5 and added only 3
    $user->roles()->sync([3,4,5]);
});
