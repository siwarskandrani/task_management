<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TaskConnect</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.min.css">
   
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- selectize.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="{{ asset('assets/imgs/favicon.svg') }}" type="image/svg+xml">

</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <div class="container-fluid">
            <div class="row flex-nowrap">
                <!-- Sidebar -->
                @include('layouts.sidebar')
                <!-- Main content -->
                <div class="col py-3">
                    @yield('dashboard')
                    @yield('profile')
                    @yield('create_team')
                    @yield('index_team')
                    @yield('edit_team')
                    @yield('create_project')
                    @yield('index_project')
                    @yield('edit_project')
                    @yield('create_task')
                    @yield('tasks_index')
                    @yield('tasks_edit')
                    @yield('tasks_member_edit')
                    @yield('calendar_task')
                    @yield('workload_content')
                    @yield('ByUser_content')
                    @yield('show_tasks')
                    @yield('show_teams')
                    @yield('content_invitations')

                </div>
            </div>
        </div>
    </div>
    @yield('scripts')


<!-- CDN for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- CDN for Selectize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
</body>
</html>
