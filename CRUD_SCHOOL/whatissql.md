What is SQL
===========

By Misael Garcia.

[Return to main page](./index.md)

Introduction
------------

SQL is a language designed for managing and manipulating data in relational databases.

SQL vs. Other Data Storage Solutions
-------------------------------

SQL offers distinct advantages over alternative data storage solutions.

SQL Commands
------------

### The SELECT Statement

The SELECT statement is used to retrieve specific data from a database.

Example:

sql
SELECT id, name, email
FROM users
WHERE active=1


### The INSERT Statement

The INSERT statement is utilized for adding new records to tables.

### The UPDATE Statement

This statement is valuable for modifying existing data within a database. 
Typically, it is followed by a WHERE clause to specify the rows to be updated based on specific conditions, often using an ID. However, it can also be used without a WHERE clause to update all rows with the same value.

...

### The DELETE Statement

The DELETE statement is employed to remove rows from a table.
...

SQL Clauses
-----------

### The WHERE Clause

The WHERE clause allows us to apply conditions to the actions we perform, such as updates or selections.

Example:

sql
SELECT name FROM users WHERE active=1;


### The ORDER BY Clause

This clause enables us to sort the result set in ascending (ASC) or descending (DESC) order based on specified column values.
...

### The GROUP BY Clause

This clause summarizes data into unique values based on a specified column, often used with aggregate functions.
...

### The LIMIT Clause

This clause restricts the number of rows returned in a query to the specified limit.
...

Other SQL Commands
------------------

### The CREATE TABLE Command

The CREATE TABLE command is used to create new tables in a database.

Example:

sql
CREATE TABLE users (
    id int primary key,
    name varchar(255),
    active tinyint
)


### The ALTER TABLE Command

This command is used to modify the structure of existing tables.
...

### The DROP TABLE Command

The DROP TABLE command is used to delete entire tables from a database.
...
