# Illuminate\Database\QueryException - Internal Server Error
Database file at path [tafeld] does not exist. Ensure this is an absolute path to the database. (Connection: sqlite, SQL: select * from "sessions" where "id" = WWr8n72uDofzC1YWwJjTTgxnbf8Db10SHcVdDmp3 limit 1)

PHP 8.3.6
Laravel 12.34.0
tafeld.test

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/Database/Connection.php:824
1 - vendor/laravel/framework/src/Illuminate/Database/Connection.php:778
2 - vendor/laravel/framework/src/Illuminate/Database/Connection.php:397
3 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:3188
4 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:3173
5 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:3763
6 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:3172
7 - vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsQueries.php:366
8 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:3095
9 - vendor/laravel/framework/src/Illuminate/Session/DatabaseSessionHandler.php:96
10 - vendor/laravel/framework/src/Illuminate/Session/Store.php:117
11 - vendor/laravel/framework/src/Illuminate/Session/Store.php:105
12 - vendor/laravel/framework/src/Illuminate/Session/Store.php:89
13 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:146
14 - vendor/laravel/framework/src/Illuminate/Support/helpers.php:390
15 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:143
16 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:115
17 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
18 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
19 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
20 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
21 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
22 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
23 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
24 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
25 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
26 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
27 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
28 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
29 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
30 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
31 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
32 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
33 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:31
34 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
35 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
36 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:51
37 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
38 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
39 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
40 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
41 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
42 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
43 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
44 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
45 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
46 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
47 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
48 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
49 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
50 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
51 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
52 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
53 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
54 - public/index.php:20

## Request

GET /

## Headers

* **priority**: u=0, i
* **sec-fetch-user**: ?1
* **sec-fetch-site**: none
* **sec-fetch-mode**: navigate
* **sec-fetch-dest**: document
* **upgrade-insecure-requests**: 1
* **cookie**: XDEBUG_SESSION=XDEBUG_ECLIPSE
* **connection**: keep-alive
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: de,en-US;q=0.7,en;q=0.3
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0
* **host**: tafeld.test

## Route Context

controller: Closure
route name: home
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

No database queries detected.
