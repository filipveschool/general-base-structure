<?php

use App\Exceptions\CouldNotDetermineCurrentUserException;
use App\Models\Settings;
use Carbon\Carbon;
use Jenssegers\Date\Date;

use App\Services\Html\Html as SpatieHtml;

function htmlfilip(): SpatieHtml {
    return app( SpatieHtml::class );
}

function roman_year( int $year = NULL ) {
    $year = $year ?? date( 'Y' );

    $romanNumerals = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1,
    ];

    $result = '';

    foreach ( $romanNumerals as $roman => $yearNumber ) {
        // Divide to get  matches
        $matches = intval( $year / $yearNumber );

        // Assign the roman char * $matches
        $result .= str_repeat( $roman, $matches );

        // Substract from the number
        $year = $year % $yearNumber;
    }

    return $result;
}

function diff_date_for_humans( Carbon $date ) {
    return ( new Date( $date->timestamp ) )->ago();
}

function locales() {
    return collect( config( 'app.locales' ) );
}

function localeUrl() {
    \Log::error( 'localeUrl' );


    if ( Session::has( 'locale' ) ) {
        \Log::info( 'session has localurl' );

        return Session::get( 'locale', app()->getLocale() );

    } else {
        //App::setLocale( 'en' );
        \Log::info( 'localurl return en' );

        //return 'en';
        //return app()->getLocale();
        //return App::setLocale()
        return Session::get( 'locale', 'nl' );
    }

    //return app()->getLocale();
}

function locale() {
    return app()->getLocale();
}

function login_url() {
    \activity()->log( 'login_url methode' );

    return request()->isFront() ?
        action( 'Front\Auth\LoginController@showLoginForm' ) :
        action( 'Back\Auth\LoginController@showLoginForm' );
}

function logout_url() {
    return request()->isFront() ?
        action( 'Front\Auth\LoginController@logout' ) :
        action( 'Back\Auth\LoginController@logout' );
}

function current_user() {
    \activity()->log( 'current_user methode' );

    if ( request()->isFront() ) {
        //return auth()->guard('front')->user();
        return auth()->user();
    }

    if ( request()->isBack() ) {
        return auth()->user();
        //return auth()->guard('back')->user();
    }

    throw new CouldNotDetermineCurrentUserException( 'Could not determine current user' );
}


function site_name() {
    return Settings::first()->site_name;
}

function site_url() {
    return Settings::first()->site_url;
}

function email_from() {
    return Settings::first()->email_from;
}

function email_to() {
    return Settings::first()->email_to;
}

function register_url(): string {
    return action( 'Front\Auth\RegisterController@showRegistrationForm' );
}

function translate_field_name( string $name, string $locale = '' ): string {
    $locale = $locale ?? content_locale();

    return "translated_{$locale}_{$name}";
}

function content_locale() {
    return \App\Services\Locale\CurrentLocale::getContentLocale();
}