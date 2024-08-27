@extends('mail.layouts.default')

@section('data')
<div style="display: flex;flex-direction: row; align-items: center;gap:100px;">
    <table id="u_content_text_1" style="font-family: 'Nunito Sans', sans-serif;"
           role="presentation" >
        <tbody>
        <tr>
            <td class="v-container-padding-padding"
                style="overflow-wrap:break-word;word-break:break-word;padding:40px 0px 0px;font-family: 'Nunito Sans', sans-serif;"
            >

                <div class="v-text-align"
                     style="line-height: 140%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 14px; line-height: 140%; text-align: left; margin-bottom: 4px;">
                                                    <span
                                                        style="font-size: 28px; line-height: 39.2px; font-weight:
                                                        800; display: block;width: max-content ">{{ $subject }}</span>
                    </p>
                    <p style="font-size: 14px; line-height: 140%; text-align: left;"><span
                            style="font-size: 17px; line-height: 22.4px; font-weight: 600; color: #000000;">{{ $user->email }}</span>
                    </p>
                </div>

            </td>
        </tr>
        </tbody>
    </table>
</div>
<table id="u_content_text_2" style="font-family: 'Nunito Sans', sans-serif;"
       role="presentation" >
    <tbody>
        <tr>
            <td class="v-container-padding-padding"
                style="overflow-wrap:break-word;word-break:break-word;padding:38px 0px 17px;font-family: 'Nunito Sans', sans-serif;"
                >

                <div class="v-text-align"
                     style="line-height: 140%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 13px; line-height: 18px; letter-spacing: 0.01em;">
                        To continue with your email verification, please enter the following
                        code: </p>
                </div>

            </td>
        </tr>
    </tbody>
</table>
<table id="u_content_text_5" style="font-family: 'Nunito Sans', sans-serif;"
       role="presentation" >
    <tbody>
        <tr>
            <td class="v-container-padding-padding"
                style="overflow-wrap:break-word;word-break:break-word;padding:0px 0px 38px;font-family: 'Nunito Sans', sans-serif;"
                >

                <div class="v-text-align"
                     style="line-height: 140%; text-align: left; word-wrap: break-word;">
{{--                    <p style="font-size: 14px; line-height: 140%; background: #F3F4FA; border-width: 1px; border-style: solid; border-color: #C7CADE; border-radius: 8px; display: inline-flex; justify-content: center; min-width: 272px; text-align: center;">--}}
                        <a
                            href="{{env('APP_URL').'/v1/auth/verify?email_verify_code='.$user->email_verification_code}}" style="font-size: 22px; line-height: 30.8px; padding-right: calc(40px - 1em); padding-top: 11px; padding-bottom: 11px; padding-left: 40px; letter-spacing: 1em; font-weight: 700;">{{ $user->email_verification_code }}</a>
{{--                    </p>--}}
                </div>

            </td>
        </tr>
    </tbody>
</table>

    <table id="u_content_text_6" style="font-family: 'Nunito Sans', sans-serif;"
           role="presentation" >
        <tbody>
            <tr>
            <td class="v-container-padding-padding"
                style="overflow-wrap:break-word;word-break:break-word;padding:0px 0px 32px;font-family: 'Nunito Sans', sans-serif;"
               >
                <div class="v-text-align"
                     style="line-height: 100%; text-align: left; word-wrap: break-word;">
                    <p style="font-size: 15px; line-height: 15px; margin-bottom: 0;">Best regards,</p>
                </div>
            </td>
            </tr>
        </tbody>
    </table>
    <table >
        <tr>
            <td style="padding: 0px;background-color: transparent;" >
                <p style="font-size: 13px; line-height: 140%; text-align: center; background: rgba(243, 244, 250, 0.5); padding-top: 15px; padding-bottom: 15px; padding-left: 15px;padding-right: 15px; opacity: 0.5;">
                    If you didn`t request a code, you can safely ignore this email</p>
            </td>
        </tr>
    </table>
@endsection
