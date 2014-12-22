Simple Validator - Easy to use validator library for PHP
========================================================

Simple Validator is a PHP library to perform data validation. Nothing more, nothing less.
You don't need to have a full bloated framework or an overkill ORM to validate data.
This library is intented to be used inside your models.

Features
--------

- Simple and easy to use
- No dependencies
- Validators: AlphaNumeric, Email, Integer, Length, Numeric, Range, Required, Unique, MacAddress, etc...


Requirements
------------

- PHP >= 5.3


Author
------

Frédéric Guillot: [http://fredericguillot.com](http://fredericguillot.com)


Source code
-----------

On Github: [https://github.com/fguillot/simpleLogger](https://github.com/fguillot/simpleValidator)


License
-------

MIT


Usage
-----

Your data must be an array, it can be form data, a decoded JSON string or whatever.
Each different validator is a simple standalone class.
But we use a main class to pass through every validators.
Let's see a basic example:

	use SimpleValidator\Validator;
	use SimpleValidator\Validators;

	$data = array('field1' => '1234');

	$v = new Validator($data, array(
		new Validators\Required('field1', 'field1 is required'),
		new Validators\Integer('field1', 'field1 must be an integer'),
	));

	// Validation error
	if (! $v->execute()) {

		print_r($v->getErrors()); // Get all validation errors
	}


Validators
----------

### Alphanumeric

Allow only letters and digits.

	new Validators\AlphaNumeric('column name', 'error message');

### Email

I use the same validation method as Firefox: <http://hg.mozilla.org/mozilla-central/file/cf5da681d577/content/html/content/src/nsHTMLInputElement.cpp#l3967>

So email address like toto+titi@domain.tld are allowed.

	new Validators\Email($field, $error_message);

### Integer

Allow only integer, but also strings with digits only.

	new Validators\Integer($field, $error_message);

### Length

Allow only strings with a correct length.

	new Validators\AlphaLength($field, $error_message, $min, $max);

### Numeric

Allow float, integer and strings with only digits and dot.

	new Validators\Numeric($field, $error_message);

### Range

Allow only numbers inside the specified range.

	new Validators\Range($field, $error_message, $min, $max);

### Required

The specified field must exists.

	new Validators\Required($field, $error_message);

### Unique

Check inside a database if the column value is unique or not.

	new Validators\Unique($field, $error_message, PDO $pdo, $table, $primary_key = 'id');

`$pdo` must be an PDO instance, `$table` is the table name and by default the primary key is "id".
If the primary key value is not null, we don't check the uniqueness of the column for this row.
It's useful if you perform validation for an update.
