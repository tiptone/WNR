Whisky Network Reviews
=======================

Introduction
------------
A simple front end to the Whisky Network Review Archive. Updating the data
is as simple as exporting a copy of the Google Docs spreadsheet as .csv
and using it as an input to createDb.php.

Run the code below to fetch a copy of the current archive and create an SQLite3
database in the file `reviews-YYYY-MM-DD.db`.

```
php -f createDb.php
```
