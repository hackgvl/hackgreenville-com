@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Slack Sign-up and Login Info')
@section('description', 'The sign-up form to request access to the HackGreenville Slack.')

@section('content')
    <div class="slack-signup">

        <iframe id="typeform-full" width="100%" height="100%" frameborder="0" src="https://hackgreenville.typeform.com/to/sBMjCF?typeform-embed=embed-fullpage"></iframe>
        <script type="text/javascript" src="https://embed.typeform.com/embed.js"></script>
    </div>
@endsection

