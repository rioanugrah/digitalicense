<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('layouts.backend.head')
</head>
<body data-topbar="dark">
    <div id="layout-wrapper">
        @include('layouts.backend.topbar')
        @include('layouts.backend.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.backend.footer')
        </div>
    </div>
    @include('layouts.backend.right-sidebar')
    @include('layouts.backend.vendor-scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    {{-- <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('8a97a9ece7a52380536a', {
        cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script> --}}

    {{-- <script type="text/javascript">
        var notificationsWrapper = $('.dropdown-notifications');
        var notificationsToggle = notificationsWrapper.find('button[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount = parseInt(notificationsCountElem.data('count'));
        var notifications = notificationsWrapper.find('div.notification-items');

        if (notificationsCount <= 0) {
            notificationsWrapper.hide();
        }

        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            encrypted: true,
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('notification');

        // Bind a function to a Event (the full Laravel class)
        channel.bind('App\\Events\\NotificationEvent', function(data) {
            // alert(data.message);
            var existingNotifications = notifications.html();

            var newNotificationHtml = `
            <a href="` + data.url + `" class="text-reset notification-item mark-as-read" data-id="` + data.id + `">
                <div class="d-flex">
                    <div class="flex-shrink-0 avatar-sm me-3">
                        <span class="avatar-title bg-` + data.color + ` rounded-circle font-size-16">
                            <i class="` + data.icon + `"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mt-0 mb-1">` + data.title + `</h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">` + data.description + `</p>
                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> ` + data.publish + `</p>
                        </div>
                    </div>
                </div>
            </a>
        `;
            notifications.html(newNotificationHtml + existingNotifications);

            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();
            // alert(data.message);
        });
    </script> --}}
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });
        });
        let _token = $('meta[name="csrf-token"]').attr('content');
        function sendMarkRequest(id) {
            return $.ajax("{{ route('markNotification') }}", {
                method: 'POST',
                data: {
                    _token,
                    id
                }
            });
        }
        $(function() {
            $('.mark-as-read').click(function() {
                let request = sendMarkRequest($(this).data('id'));
                request.done(() => {
                    $(this).parents('div.alert').remove();
                });
            });
            $('#mark-all').click(function() {
                let request = sendMarkRequest();
                request.done(() => {
                    $('div.alert').remove();
                })
            });
        });
    </script>
</body>
</html>
