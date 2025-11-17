# Symfony\Component\Routing\Exception\RouteNotFoundException - Internal Server Error

Route [dashboard] not defined.

PHP 8.3.6
Laravel 12.37.0
tafeld.test

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/Routing/UrlGenerator.php:526
1 - vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:871
2 - resources/views/livewire/pages/auth/register.blade.php:35
3 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
4 - vendor/laravel/framework/src/Illuminate/Container/Util.php:43
5 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
6 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
7 - vendor/livewire/livewire/src/Wrapped.php:23
8 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:492
9 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:101
10 - vendor/livewire/livewire/src/LivewireManager.php:102
11 - vendor/livewire/volt/src/LivewireManager.php:35
12 - vendor/livewire/livewire/src/Mechanisms/HandleRequests/HandleRequests.php:94
13 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
14 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
15 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
16 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
17 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
18 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
19 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
20 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
21 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
22 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
23 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
24 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
25 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
26 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
27 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
28 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
29 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
30 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
31 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
32 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
33 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
34 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
35 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
36 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
37 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
38 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
39 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
40 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:27
41 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
42 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:47
43 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
44 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
45 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
46 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
47 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
48 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
49 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
50 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
51 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
52 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
53 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
54 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
55 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
56 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
57 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
58 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
59 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
60 - public/index.php:20

## Request

POST /livewire/update

## Headers

* **priority**: u=4
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: cors
* **sec-fetch-dest**: empty
* **cookie**: XDEBUG_SESSION=XDEBUG_SESSION=1; XSRF-TOKEN=eyJpdiI6IlFFUnQ3djZFZVdYZkN2TXZnV2prQkE9PSIsInZhbHVlIjoic21KZEg3bmlybmE3dzFHUEJhRDNHRUNKbUR0OGF3bDljOHZwa3I3dHVVYlNoeENVV3IyNEVTNnJRUjNST21tNkxPcDJhUm5qaFVpak9ibzFzMis2a0VHSkVBUHVJS2ZhM2F4anMzbXVMMmFWeFlsUktMQjExVnZTakE4K3QwS0kiLCJtYWMiOiJhMzg0Y2MwM2QyYjE1ODQ3YzBkYTU3ZTViMDY4OTcxOTljNzY3M2VlMmUyNmViMjUzODdiZDAzNzQ4ODQ3ZGFlIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IlNEaVMwMGpXQ3UrS3JjNjhJdWE1MWc9PSIsInZhbHVlIjoiNkxOWk9IQ0dNbXZvZnJLd1RxZnNwd0NRNHI3UnZtdnlJaVVqaEZCbWZVaXlxOXZ1TTlUMm1CL0FZaDNITTB0aDM1NDRJUGhpc21STWkySS9CN2dqMUZqY0RmdWluSVlUbEhZazZXbVdDOTJkZTZQTHFCdlpwR1lVekRMRzFsbnAiLCJtYWMiOiI0ZTk4MzliM2ZlZmQxNTdlMGFjZGEzNjA4ZmIxMjhmNWEzODk4YmFiOWQ2MjhkNTZmMGMwZWEzYjRiNDU2ZTUyIiwidGFnIjoiIn0%3D
* **connection**: keep-alive
* **origin**: https://tafeld.test
* **content-length**: 623
* **x-livewire**: 
* **content-type**: application/json
* **referer**: https://tafeld.test/register
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: de,en-US;q=0.7,en;q=0.3
* **accept**: */*
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0
* **host**: tafeld.test

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: livewire.update
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select * from "cache" where "key" in ('laravel-cache-telescope:dump-watcher') (7.09 ms)
* pgsql - select * from "sessions" where "id" = 'WhnBVOlcpPDUevvmobQf3xoMge8Aj0Ydkmy5nUsh' limit 1 (0.83 ms)
* pgsql - select count(*) as aggregate from "users" where "email" = 'gunreip@web.de' (1.15 ms)
* pgsql - insert into "users" ("name", "email", "password", "updated_at", "created_at") values ('Gunter Reinhard', 'gunreip@web.de', 'y$WJt2X.JC55q0juBpQHJ0j.CUVUq9RbfePXza.fuJeuf/aSb.FHVQO', '2025-11-09 18:06:05', '2025-11-09 18:06:05') returning "id" (5 ms)
* pgsql - delete from "sessions" where "id" = 'WhnBVOlcpPDUevvmobQf3xoMge8Aj0Ydkmy5nUsh' (1.95 ms)
