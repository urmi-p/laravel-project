<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">

    <ul style="margin: 0px; padding: 0px; list-style-type: none; width: 100%; text-align: center;">
        <li style="display: inline-block;"><a href="{{ AdminSettings::value('facebook') }}"
                style="color: #bdbdbd; text-decoration: none;">
                <img src="{{ url('public/img/facebook-square-white-bordered.png') }}" width="40">
            </a>
        </li>
        <li style="display: inline-block;"><a href="{{ AdminSettings::value('twitter') }}"
                style="color: #bdbdbd; text-decoration: none;">
                <img src="{{ url('public/img/x-square-white-bordered.png') }}" width="40">
            </a>
            </li>
        <li style="display: inline-block;"><a href="{{ AdminSettings::value('instagram') }}"
                style="color: #bdbdbd; text-decoration: none;">
                <img src="{{ url('public/img/instagram-square-white-bordered.png') }}" width="40">
            </a>
            </li>
    </ul>
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
