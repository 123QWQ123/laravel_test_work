@foreach($currencies as $key => $currency)
    <div class="card">
        <div class="card-header">
            <div class="card-link" data-toggle="collapse" href="#collapse{{"{$currency['from']['curr_id']}-{$currency['to']['curr_id']}"}}">
                {{ "{$currency['from']['curr_code']}/{$currency['to']['curr_code']}" }}
            </div>
        </div>
        <div id="collapse{{"{$currency['from']['curr_id']}-{$currency['to']['curr_id']}"}}" class="collapse" data-parent="#accordion">
            <div class="card-body">
                <span>{{ $currency['rate']['rate'] }}</span>
            </div>
        </div>
    </div>
@endforeach
