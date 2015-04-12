# CS4400 project
100% CS4400 spring'15 project 

---

## Setup

You'll first need to make sure you have `npm` installed globally in your system.

Run the following commands in your terminal:

```
npm install
bower install
```

Then open up MAMP, XAMPP, etc. and everything should work.

**Note 1: You might need to change the config options for the database, which I've put in `resources/config.php`. Make sure you
don't commit `config.php` to git, because it can screw everyone's custom server configurations.**

Note 2: There seems to be a weird visual glitch in Google Chrome. For demoing, let's just use Firefox.

## Getting Started

I wrote some framework pieces that should dramatically reduce coding time and cognitive overhead.
The best way to understand what to do is to go through the code I've posted so far (logging in, logging out, the profile page, and registration page).


Here's the cliffnotes for dealing with form stuff, though:

1) Write your validators first (name is required, etc.)

2) Initialize your form class

3) specify what validator to use for the form

4) add code that fires when triggers happen. I support onSubmit() and onRetrieve().

5) Write the actual form. All validation, logic, css styling, html structure, CRUD, etc. is all handled automatically if you follow these rules. You should be able to declaratively specify all the major form elements.

6) If you need to get the current user, you should use the `User` class. It has convenience methods such as `login()`, `getUsername()`, `logout()`, etc.
Tip: if you want to get the username or password for the currently logged in user, use: `(new User('', ''))->getUsername()` or `(new User('', ''))->getPassword()`.

---

## SQL Fixes

The *Search Books* SQL code we submitted was incorrect. I changed it to this, and it works:

```
SELECT b.Title, b.Edition, b.ISBN FROM Book AS b
 INNER JOIN Author AS a
 ON a.ISBN=b.ISBN
 WHERE (
  b.ISBN LIKE '%$isbn%' AND
  b.Title LIKE '%$title%' AND
  a.Author LIKE '%$author%'
 )
```