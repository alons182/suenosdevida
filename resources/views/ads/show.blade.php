@extends('layouts.layout')

@section('content')
<section class="main ads">
    <h1>Anuncio</h1>
    <h2>{!! $ad->name !!}</h2>
    <div class="ads-video">
        {!! $ad->video !!}
    </div>
    <div class="ads-description">
        <p>{!! $ad->description !!}</p>
    </div>
    <div class="ads-comments">
        {!! Form::open([ 'route'=>['ads.comment', $ad->id] ,'class'=>'form-contact']) !!}


        <div class="form-group">
            {!! Form::label('comment','Deja tu comentario:') !!}
            {!! Form::textarea('comment',null,['class'=>'form-control','disabled' => 'disabled', 'id' => 'comment']) !!}
            {!! errors_for('comment',$errors) !!}
        </div>
        <div class="form-group">

            {!! Form::submit('Enviar',['class'=>'btn btn-primary','disabled' => 'disabled'])!!}
            {!! link_to_route('payments.index','Regresar')!!}
        </div>

        {!! Form::close() !!}
        <div class="countdown"></div>
    </div>
</section>

@stop
@section('scripts')
    <script>
        TargetDate = "{!! $targetDate !!}"//"03/28/2015 15:40";
        BackColor = "#98ba33";
        ForeColor = "white";
        CountActive = true;
        CountStepper = -1;
        LeadingZero = true;
        DisplayFormat = "%%M%% Minutes, %%S%% Seconds.";
        FinishMessage = "Ya puedes enviar tu comentario";
        console.log(TargetDate);
        function calcage(secs, num1, num2) {
            s = ((Math.floor(secs/num1))%num2).toString();
            if (LeadingZero && s.length < 2)
                s = "0" + s;
            return "<b>" + s + "</b>";
        }

        function CountBack(secs) {
            if (secs < 0) {
                document.getElementById("cntdwn").innerHTML = FinishMessage;
                $(".btn").attr('disabled',false);
                $("#comment").attr('disabled',false);
                return;
            }
            DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
            DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
            DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
            DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

            document.getElementById("cntdwn").innerHTML = DisplayStr;
            if (CountActive)
                setTimeout("CountBack(" + (secs+CountStepper) + ")", SetTimeOutPeriod);
        }

        function putspan(backcolor, forecolor) {
            $(".countdown").html("<span id='cntdwn' style='background-color:" + backcolor +
            "; color:" + forecolor + "'></span>");
        }

        if (typeof(BackColor)=="undefined")
            BackColor = "white";
        if (typeof(ForeColor)=="undefined")
            ForeColor= "black";
        if (typeof(TargetDate)=="undefined")
            TargetDate = "12/31/2015 5:00 AM";
        if (typeof(DisplayFormat)=="undefined")
            DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
        if (typeof(CountActive)=="undefined")
            CountActive = true;
        if (typeof(FinishMessage)=="undefined")
            FinishMessage = "";
        if (typeof(CountStepper)!="number")
            CountStepper = -1;
        if (typeof(LeadingZero)=="undefined")
            LeadingZero = true;


        CountStepper = Math.ceil(CountStepper);
        if (CountStepper == 0)
            CountActive = false;
        var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
        putspan(BackColor, ForeColor);
        var dthen = new Date(TargetDate);
        var dnow = new Date();
        if(CountStepper>0)
            ddiff = new Date(dnow-dthen);
        else
            ddiff = new Date(dthen-dnow);
        gsecs = Math.floor(ddiff.valueOf()/1000);
        CountBack(gsecs);

    </script>



@stop