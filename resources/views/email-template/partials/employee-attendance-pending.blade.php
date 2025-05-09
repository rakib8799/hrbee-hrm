@extends('email-template.email-layout')

@section('content')
<tr>
    <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
        <!--begin:Email content-->
        <div style="text-align:center; margin:0 15px 34px 15px">
            <!--begin:Logo-->
            {{-- <div style="margin-bottom: 10px">
                <h1>{{ $user['name'] }}</h1>
            </div> --}}
            <!--end:Logo-->
            <!--begin:Media-->
            {{-- <div style="margin-bottom: 15px">
                <img alt="Logo" src="{{ asset('assets/media/email/icon-positive-vote-1.svg') }}" />
            </div> --}}
            <!--end:Media-->
            <!--begin:Text-->
            <div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
                <p style="margin-bottom:2px; color:#181C32; font-size: 22px; font-weight:700">Hey {{ $attendanceRequest->employee->first_name }} {{ $attendanceRequest->employee->last_name }}, </p>
                <p style="color: #F6C008; font-weight: bold;">
                    Your attendance request is pending for the date {{ $attendanceRequest->attendance_date }}.
                </p>
            </div>
            <!--end:Text-->
            <!--begin:Action-->
            <a href="{{ $redirectionLink }}" target="_blank" style="background-color:#50cd89; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500; text-decoration: none;">Attendance Request</a>
            <!--begin:Action-->

            <p>Nondito Soft Team</p>
        </div>
        <!--end:Email content-->
    </td>
</tr>
<tr>
    <td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
        <p style="color:#181C32; margin-bottom:9px">We are here 24/7 for you</p>
        <p style="margin-bottom:2px"><span>{{ $supportDetails['supportTelephone'] }}</span> | <a href="mailto:{{ $supportDetails['supportEmail'] }}" rel="noopener" target="_blank" style="font-weight: 600">{{ $supportDetails['supportEmail'] }}</a> | <a href="https://hrbee.mkrdev.xyz/faqs" target="_blank" style="font-weight: 600">FAQ</a></p>
    </td>
</tr>
{{-- <tr>
    <td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
        <a href="#" style="margin-right:10px">
            <img alt="Logo" style="height: 50px;" src="{{ asset('media/logos/mkr-logo.png') }}" />
        </a>
    </td>
</tr> --}}
<tr>
    <td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif; padding-top: 20px;">
        <img alt="Logo" style="height: 30px;" src="{{ asset('media/logos/mkr-logo.png') }}" />
        <p>&copy; {{ now()->year }} hr bee</p>
        <p><a href="https://hrbee.mkrdev.xyz" rel="noopener" target="_blank" style="font-weight: 600;font-family:Arial,Helvetica,sans-serif">Unsubscribe</a>&nbsp;</p>
    </td>
</tr>
@endsection
