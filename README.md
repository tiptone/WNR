Whisky Network Reviews
=======================

Introduction
------------
A simple front end to the Whisky Network Review Archive. Updating the data
is as simple as exporting a copy of the Google Docs spreadsheet as .csv
and using it as an input to createDb.php.

```
php -f createDb.php -- -i GoogleDocs.csv -o reviews.db
```