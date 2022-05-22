<meta name="csrg-token" content="{{csrf_token()}}">

<script src="{{asset("js/app.js")}}">

    Echo.channel('channel-name')
    .listen('NewMessage', (e) => {
        console.log(e.message);
    })
</script>
