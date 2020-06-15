<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speed extends Model
{
    //
    
	protected $connection = 'speed';

	protected $table = 'wp_posts';
	protected $casts = [
		'stock' => 'integer',
		
    ];
	
	/*protected $connection = 'speed2';

	protected $table = 'pruebausuario';
	*/
}
