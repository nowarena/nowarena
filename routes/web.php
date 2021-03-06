<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
//Route::get('/', 'WelcomeController@welcome')->name('welcome');

Route::domain('{subdomain}.nowarena.com')->group(function () {
    if (!isset($_SERVER['HTTP_HOST'])) {
        exit("HTTP_HOST not set");
    }
    $arr = explode(".", $_SERVER['HTTP_HOST']);
    if (count($arr) < 3) {
        exit("subdomain not set");
    }
    $subdomain = $arr[0];
    Route::get('/', 'WelcomeController@' . $subdomain)->name($subdomain);
});

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController', [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::resource('themes', 'ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('php', 'AdminDetailsController@listPHPInfo');
    Route::get('routes', 'AdminDetailsController@listRoutes');
});

//exit($_SERVER['REQUEST_URI']);
// Tasks
//Route::get('/tasks','TasksController@index')->name('tasks.index');
//Route::get('/tasks/create', 'TasksController@create')->name('tasks.create');
//Route::post('/tasks', 'TasksController@store')->name('tasks.store');
//Route::get('/tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
//Route::post('/tasks/{task}/update', 'TasksController@update')->name('tasks.update');
//Route::get('/tasks/{task}', 'TasksController@destroy')->name('tasks.delete');

// category editor
Route::get('/cats','CatsController@index')->name('cats.index');
Route::get('/cats/create', 'CatsController@create')->name('cats.create');
Route::post('/cats', 'CatsController@store')->name('cats.store');
Route::get('/cats/{cats}/edit', 'CatsController@edit')->name('cats.edit');
Route::post('/cats/{cats}/update', 'CatsController@update')->name('cats.update');
Route::get('/cats/{cats}', 'CatsController@destroy')->name('cats.delete');

// items
Route::get('/items','ItemsController@index')->name('items.index');
// use 'create'
Route::get('/items/listsocialmediaaccounts', 'ItemsController@create')->name('items.listsocialmediaaccounts');
// use 'edit'
Route::get('/items/{items}/updatesocialmediaaccounts', 'ItemsController@updatesocialmediaaccounts')->name('items.updatesocialmediaaccounts');

Route::post('/items', 'ItemsController@store')->name('items.store');
Route::post('/items/{items}/update', 'ItemsController@update')->name('items.update');
Route::get('/items/{items}', 'ItemsController@destroy')->name('items.delete');
// this is 404
Route::post('/items/updateitemcat', 'ItemsController@updateItemCat');

// links
Route::get('/links','LinksController@index')->name('links.index');
Route::get('/links/create', 'LinksController@create')->name('links.create');
Route::post('/links', 'LinksController@store')->name('links.store');
Route::get('/links/{links}/edit', 'LinksController@edit')->name('links.edit');
Route::post('/links/{links}/update', 'LinksController@update')->name('links.update');
Route::get('/links/{links}', 'LinksController@destroy')->name('links.delete');

//Route::get("/index", "InstagramController@index")->name("instagram.index");
//// Homepage Route
//Route::get('/', 'WelcomeController@welcome')->name('welcome');
Route::resource('instagram','InstagramController');

Route::get('/twitter/getfeed', 'TwitterController@create')->name('twitter.create');
Route::get('/twitter/store', 'TwitterController@store')->name('twitter.store');
Route::get('/twitter/getfriends', 'TwitterController@show')->name('twitter.show');
Route::get('/twitter/index', 'TwitterController@index')->name('twitter.index');
Route::get('/twitter/getmemberlists', 'TwitterController@getmemberlists')->name('twitter.getmemberlists');

Route::get("read", "ReadController");

Route::resource('yelp', 'YelpController');