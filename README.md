<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## About IAM
Iam is an Asset Management web-based application then called ARMS (Asset Resource Management Systems) for asset registering, asset transaction recording, and asset validating. Assets are
every computers, laptops, or anything else controlled by IT Departement that lies on every company site projects and having a physical existence. They can be counted and transfered to other user "handover" which account on a transaction receipt and database table. So the assets existence are always tracked by the IT Departement.

## Installation
This project requires
<ul>
    <li>PHP 8.*</li>
    <li>Extension postgresql</li>
    <li>Bootstrap v5</li>
    <li>Jquery 3</li>
    <li>maatwebsite/excel</li>
</ul>
<br>
Enable postgresql in your xampp
<br>
Download Bootstrap V5 here -> https://github.com/twbs/bootstrap/releases/download/v5.0.2/bootstrap-5.0.2-dist.zip
<br>
Download Jquery 3 here -> https://code.jquery.com/jquery-3.7.1.min.js
<br>
Add laravel excel in the project 
<br>
<code>composer require maatwebsite/excel</code>

## Who Are The Users
The user consists of staff who was given an asset or assets by the IT Departement. They have a copy of transaction receipt and have responsibilites to protect the handed asset by any chance. The pinalty of being careless is the user must bring the broken asset to the repairement, pay some cash, or for the worst circumtences the user must exchange the asset with the new one.

## The Admin Team
Admin consists of Super Admin and Admin.
    1.  Super Admin - have privileges to control all tables, and using CRUD on admin table
    2.  Admin - we can say he is a data entry and data modifier for assets, offices, users, transactions, and validations. Making
        sure all these tables are filled by correct records.

## Still On Updates Or End Of Development
This project was ended by Afif the developer because of he had gradually finish his occupation contract in PT SMCC Utama Indonesia and will not going to finish or making updates on this project anymore.
