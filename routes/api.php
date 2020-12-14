<?php

//Public
Route::get('/',function(){
   return response()->json([ 'message' => "Hello worlds"]);
});
Route::get('me','User\MeController@getMe');
Route::get('designs','Designs\DesignsController@index');
Route::get('users','User\UsersController@index');

//Teams
Route::get('teams/name/{slug}','Teams\TeamsController@findBySlug');


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
    Route::resource('designs','Designs\DesignsController',['only'=>['update','show','destroy']]);

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

    //Invitations
    Route::post('invitations/{team}','Teams\InvitationsController@invite');
    Route::post('invitations/{invitation}/resend','Teams\InvitationsController@resend');
    Route::post('invitations/{invitation}/respond','Teams\InvitationsController@respond');
    Route::delete('invitations/{invitation}','Teams\InvitationsController@destroy');
});