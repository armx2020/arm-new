@props(['selectedCity' => null])

<label for="city" class="text-sm font-medium text-gray-900 block mb-2">Город</label>
<select name="city" class="w-full" id="city">
    @if (isset($selectedCity))
        <option value="{{ $selectedCity->id }}"> {{ $selectedCity->name }}</option>
    @else
        <option value='1'>-- выбор города --</option>
    @endif
</select>

<script type="text/javascript">
    if ($("#city").length > 0) {
        $("#city").select2({
            ajax: {
                url: " {{ route('admin.get-city') }}",
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        query: params.term || '',
                        page: params.page || 1,
                        "_token": "{{ csrf_token() }}",
                    };

                    return query;
                },
                processResults: function(response, params) {
                    params.page = params.page || 1;
                    return {
                        results: response.results,
                        pagination: {
                            more: response.pagination.more
                        }
                    };
                },
                cache: true
            }
        });
    }
</script>

<style>
    .select2-container--default .select2-selection--single {
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
        border-color: rgb(209 213 219));
        border-radius: 0.5rem;
        height: 42px;
    }
    .select2, .select2-container, .select2-container--default{
        width: 100% !important;
    }

</style>
