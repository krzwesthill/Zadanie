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
*/


Route::get('/import/{code?}', function ($code = null) {

	if(isset($code)) {
		$import = App\Offer::where('kod_produktu',"=",$code)->get();
	}
	else {
		$import = App\Offer::all();	
	}

	$stats = App\Stats::all()->toArray();	

    return view('import', ['import' => $import, 'stats' => $stats]);
})->name("import");

