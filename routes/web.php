<?php

use Illuminate\Support\Facades\Route;
use App\User;
use App\Address;
use App\Post;
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
