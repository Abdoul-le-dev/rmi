@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1>Subscription Expiration Reminder</h1>
        <p>
            Dear {{ $user->name }},
        </p>
        <p>
            Your subscription will expire in {{ $remainingDays }} day(s). Please resubscribe in time to keep your
            account active.
        </p>
    </td>
@endsection
