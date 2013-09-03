<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used
	| by the validator class. Some of the rules contain multiple versions,
	| such as the size (max, min, between) rules. These versions are used
	| for different input types such as strings and files.
	|
	| These language lines may be easily changed to provide custom error
	| messages in your application. Error messages for custom validation
	| rules may also be added to this file.
	|
	*/

	"accepted"       => "El kell fogadnia a :attribute.",
	"active_url"     => "A mező :attribute egész URL-nek kell lenni.",
	"after"          => "A mező :attribute ennek a dátumnak kell lenni :date.",
	"alpha"          => "A mező :attribute tartalmazhat csak betűket.",
	"alpha_dash"     => "A mező :attribute csak betűket, számokat és kötőjeleket tartalmazhat.",
	"alpha_num"      => "A mező :attribute csak betűket és számokat tartalmazhat.",
	"array"          => "A mezőben :attribute ki kell valamit választani.",
	"before"         => "A mező :attribute dátumnak kell lenni :date. előtt.",
	"between"        => array(
		"numeric" => "A mező :attribute legyen :min és :max között.",
		"file"    => "A mező :attribute legyen :min és :max KB között.",
		"string"  => "A mező :attribute legyen :min és :max betű között.",
	),
	"confirmed"      => "A mező :attribute nem egyezik a mintával.",
	"count"          => "A mezőben :attribute pontosan :count kiválasztott elemnek kell lenni.",
	"countbetween"   => "A mezőben :attribute :min és :max kiválasztott elem között kell lenni.",
	"countmax"       => "A mezőben :attribute kevesebb mint :max kiválasztott elem lehet.",
	"countmin"       => "A mezőben :attribute lehet csak több mint :min kiválasztott elem.",
	"different"      => "A mező :attribute és a mező :other nem lehet azonos.",
	"email"          => "A mező :attribute rossz formátumú.",
	"exists"         => "A kiválasztott érték :attribute már létezik.",
	"image"          => "A mezőben :attribute egy képnek kell lennie.",
	"in"             => "A kiválasztott érték :attribute nem helyes.",
	"integer"        => "A mező :attribute egész számnak kell lenni.",
	"ip"             => "A mezőben :attribute egész IP címnek kell lenni.",
	"match"          => "A mező :attribute rossz formátumú.",
	"max"            => array(
		"numeric" => "A mező :attribute kissebnek kell lenni mint :max.",
		"file"    => "A mező :attribute kissebnek kell lenni mint  :max KB.",
		"string"  => "A mező :attribute rovidebbnek kell lenni mint :max betű.",
	),
	"mimes"          => "A mező :attribute csak az alábbi fájltípusokat tartalmazhat: :values.",
	"min"            => array(
		"numeric" => "A mező :attribute nagyobbnak kell lenni mint :min.",
		"file"    => "A mező :attribute nagyobbnak kell lenni mint :min KB.",
		"string"  => "A mező :attribute hosszabbnak kell lenni mint :min betű.",
	),
	"not_in"         => "A kiválasztott érték :attribute nem helyes.",
	"numeric"        => "A mező :attribute számnak kell lenni.",
	"required"       => "A mezőt :attribute ki kell tolteni.",
	"same"           => "Az értéknek :attribute meg kell felelni a :other.",
	"size"           => array(
		"numeric" => "A mező :attribute hossza kell hogy az alábbi legyen: :size.",
		"file"    => "A mező :attribute kell hogy a hossza legyen: :size KB.",
		"string"  => "A mező :attribute kell hogy a hossza legyen :size betű.",
	),
	"unique"         => "Ez az érték :attribute már létezik.",
	"url"            => "A mezőnek :attribute rossz formátuma van.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute_rule" to name the lines. This helps keep your
	| custom validation clean and tidy.
	|
	| So, say you want to use a custom validation message when validating that
	| the "email" attribute is unique. Just add "email_unique" to this array
	| with your custom message. The Validator will handle the rest!
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as "E-Mail Address" instead
	| of "email". Your users will thank you.
	|
	| The Validator class will automatically search this array of lines it
	| is attempting to replace the :attribute place-holder in messages.
	| It's pretty slick. We think you'll like it.
	|
	*/

	'attributes' => array(),

);