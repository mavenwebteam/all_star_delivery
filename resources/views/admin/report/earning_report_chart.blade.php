<div style="width: 99%; height: 250px" id="graph-0" class="x_panel">
    {!! $earningChart->container() !!}
</div>

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $earningChart->script() !!}
@endpush