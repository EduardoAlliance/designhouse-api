<?php

//Public
Route::get('/',function(){
   return response()->json([ 'message' => "Hello worlds"]);
});


Route::get('me','User\MeController@getMe');

//Designs
Route::get('designs','Designs\DesignsController@index');
Route::get('designs/name/{slug}','Designs\DesignsController@findBySlug');

//Users
Route::get('users','User\UsersController@index');
Route::get('users/{user}/designs','User\UsersController@getDesignsForUser');


//Teams
Route::get('teams/name/{slug}','Teams\TeamsController@findBySlug');
Route::get('teams/{team}/designs','Teams\TeamsController@getDesigns');


//Search Designs
Route::get('search/designs','Designs\DesignsController@searchDesigns');

//Search Designers
Route::get('search/designers','User\UsersController@searchDesigners');




//Guest
Route::group(['middleware'=> ['guest:api']],function (){
    Route::post('register','Auth\RegisterController@register');
    Route::get('verification/verify','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend');
    Route::post('login','Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');

});

//Authenticated
Route::group(['middleware'=> ['auth:api']],function (){
    Route::post('logout','Auth\LoginController@logout');

    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');

    //Designs
    Route::post('designs/upload','Designs\UploadController@upload');
    //Route::resource('designs','Designs\DesignsController',['only'=>['update','show']]);
    Route::put('designs/{design}','Designs\DesignsController@update');
    Route::delete('designs/{u_id}','Designs\DesignsController@destroy');
    Route::get('designs/{id}','Designs\DesignsController@userOwnsDesign');

    //Like Unlike Comments
    Route::post('designs/{id}/like','Designs\DesignsController@like');
    Route::get('designs/{id}/liked','Designs\DesignsController@likedByUser');

    // Comments
    Route::post('designs/{id}/comments','Designs\CommentsController@store');
    Route::put('comments/{id}','Designs\CommentsController@update');
    Route::delete('comments/{id}','Designs\CommentsController@destroy');

    // Teams
    Route::get('teams','Teams\TeamsController@index');
    Route::get('teams/{team}','Teams\TeamsController@findById');
    Route::post('teams','Teams\TeamsController@store');
    Route::put('teams/{team}','Teams\TeamsController@update');
    Route::delete('teams/{team}','Teams\TeamsController@destroy');
    Route::get('users/teams','Teams\TeamsController@getUserTeams');
    Route::delete('teams/{team}/users/{user}','Teams\TeamsController@removeFromTeam');

    //Invitations
    Route::post('invitations/{team}','Teams\InvitationsController@invite');
    Route::post('invitations/{invitation}/resend','Teams\InvitationsController@resend');
    Route::post('invitations/{invitation}/respond','Teams\InvitationsController@respond');
    Route::delete('invitations/{invitation}','Teams\InvitationsController@destroy');

    //Chats
    Route::post('chats','Chats\ChatController@sendMessage');
    Route::get('chats','Chats\ChatController@getUsersChats');
    Route::get('chats/{chat_id}/messages','Chats\ChatController@getChatMessages');
    Route::put('chats/{chat_id}/markAsRead','Chats\ChatControler@markAsRead');
    Route::delete('messages/{message_id}','Chats\ChatControler@destroyMessage');

});
