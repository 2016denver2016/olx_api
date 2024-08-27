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
                            style="font-size: 17px; line-height: 22.4px; font-weight: 600; color: #000000;">{{ $userAdvert->url }}</span>
                    </p>
                </div>

            </td>
        </tr>
        </tbody>
    </table>
</div>

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

@endsection
