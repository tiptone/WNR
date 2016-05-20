Whisky Network Reviews
=======================

Introduction
------------
A simple front end to the Whisky Network Review Archive. Updating the data
is as simple as exporting a copy of the Google Docs spreadsheet as .csv
and then using sqlite3 to .import it into a "reviews" table.

```bash
sqlite3 data/reviews.db
```

```sql
sqlite> CREATE TABLE reviews (
  timestamp text,
  whiskey text,
  reviewer text,
  url text,
  rating integer,
  region text,
  price text,
  date text
);

sqlite> .import export.csv reviews
sqlite> .quit
```
