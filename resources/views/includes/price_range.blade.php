<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<?php  if($max_price >= 0){ ?>
<li class="w-100 float-left dropdown show px-4">
    <p>
      <label for="amount">Price Range:</label>
      <input type="text" id="amount" readonly>
    </p>
    <div id="slider-range"></div> 
</li>
<?php }
    //$currentCurrencys    =   Config::get("Site.currencyCode");  
		$userCurrencys       =   "USD";
    //$currency_symbol     =   currencySymbol($userCurrencys); 
   /* if(!empty($min_price)){
            $min_price  =    convert_currencynew($currentCurrencys,$userCurrencys,$min_price);
    }
    if(!empty($max_price || $max_price >= 0)){
        $max_price  =    convert_currencynew($currentCurrencys,$userCurrencys,$max_price);
    }
    $maxValues  =    convert_currencynew($currentCurrencys,$userCurrencys,$maxValues);
	*/
 ?>
<script>
$( function() {
    //var language = "{{ Config::get('app.locale') }}";
    var minValue ="{{!empty($min_price) ? $min_price:0}}";
    var maxValue ="{{ !empty($max_price) ||  $max_price >= 0 ? $max_price : $maxValues}}";
    console.log(maxValue+" - "+minValue+ " -"+"{{$maxValues}}");
   
        $("#slider-range" ).slider({
            range: true,
            min: 0,
            max: {{$maxValues}},
            values: [ minValue, maxValue ],
            slide: function( event, ui ) {
                 console.log("ui - "+ui.values[ 0 ]+" - "+ui.values[ 1 ]);
                $( "#amount" ).val( "{{ $userCurrencys }}" + ui.values[ 0 ] + " - {{ $userCurrencys }}" + ui.values[ 1 ] );
            },
            stop: function( event, ui ) {
                console.log("ui2 - "+ui.values[ 0 ]+" - "+ui.values[ 1 ]);
                var min = ui.values[0];
                var max = ui.values[1];
                $("#min_price").val(min);
                $("#max_price").val(max);
                $("#search_products").submit();
            }
        });
        $("#amount").val("{{$userCurrencys}}" + $("#slider-range").slider("values", 0) +
        " - {{$userCurrencys}}" + $( "#slider-range" ).slider( "values", 1 ) );
   /* }else{
        $("#slider-range" ).slider({
            range: true,
            min: 0,
            isRTL: true,
            max: {{$maxValues}},
            values: [ minValue, maxValue ],
            slide: function( event, ui ) {
                 console.log("ui - "+ui.values[ 0 ]+" - "+ui.values[ 1 ]);
                $( "#amount" ).val( "{{ $userCurrencys }}" + ui.values[ 1 ] + " - {{ $userCurrencys }}" + ui.values[ 0 ] );
            },
            stop: function( event, ui ) {
                console.log("ui2 - "+ui.values[ 1 ]+" - "+ui.values[ 0 ]);
                var min = ui.values[0];
                var max = ui.values[1];
                $("#min_price").val(min);
                $("#max_price").val(max);
                $("#search_product").submit();
            }
        });

        $("#amount").val("{{$userCurrencys}}" + $("#slider-range").slider("values", 1) +
        " - {{$userCurrencys}}" + $( "#slider-range" ).slider( "values", 0 ) );
    }*/
    

   
});
</script>