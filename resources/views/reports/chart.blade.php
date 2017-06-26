@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Charts</div>

                <div class="panel-body">
                    <div id="vue-reports">
                        <report></report>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
